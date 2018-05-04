<?php

namespace Swoft\Aop\Proxy;

use App\Controllers\ValueController;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter;
use PhpParser\PrettyPrinterAbstract;
use Swoft\Aop\Ast\Parser;
use Swoft\Aop\Ast\Visitors\ProxyVisitor;
use Swoft\Core\Application;

/**
 * Proxy factory
 */
class Proxy
{

    /**
     * @var Parser
     */
    protected static $parser;

    /**
     * @var \PhpParser\PrettyPrinterAbstract
     */
    protected static $printer;

    /**
     * Return a proxy instance
     *
     * @param string $className
     * @return string
     * @throws \RuntimeException
     * @throws \ReflectionException
     */
    public static function newProxyClass(string $className): string
    {
        // Create Proxy Class
        $proxyId = \uniqid('', false);
        if ($ast = self::getParser()->getOrParse($className)) {
            // Ast Proxy Strategy
            $traverser = new NodeTraverser();
            $proxyVisitor = (new ProxyVisitor())->setClassName($className)->setProxyId($proxyId);
            $traverser->addVisitor($proxyVisitor);
            $proxyAst = $traverser->traverse($ast);
            $template = self::getPrinter()->prettyPrint($proxyAst);
            $proxyClassName = $proxyVisitor->getFullProxyClassName();
        } else {
            $reflectionClass = new \ReflectionClass($className);
            $reflectionMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED);
            $proxyClassName = \basename(str_replace("\\", '/', $className));
            $proxyClassName = $proxyClassName . '_' . $proxyId;
            $template = self::previousProxyStrategy($className, $reflectionClass, $proxyClassName, $reflectionMethods);
        }

        // Load class
        eval($template);

        return $proxyClassName;
    }

    /**
     * Return the template of method
     *
     * @param \ReflectionMethod[] $reflectionMethods
     * @return string
     */
    private static function getMethodsTemplate(array $reflectionMethods): string
    {
        $template = '';
        foreach ($reflectionMethods as $reflectionMethod) {
            $methodName = $reflectionMethod->getName();

            // not to override method
            if ($reflectionMethod->isConstructor() || $reflectionMethod->isStatic()) {
                continue;
            }

            // the template of parameter
            $template .= " public function $methodName (";
            $template .= self::getParameterTemplate($reflectionMethod);
            $template .= ' ) ';

            // the template of return type
            $reflectionMethodReturn = $reflectionMethod->getReturnType();
            if ($reflectionMethodReturn !== null) {
                $returnType = $reflectionMethodReturn->__toString();
                $returnType = $returnType === 'self' ? $reflectionMethod->getDeclaringClass()->getName() : $returnType;
                $template .= " : $returnType";
            }

            // overrided method
            $template .= "{
                return \$this->__proxy('{$methodName}', func_get_args());}";
        }

        return $template;
    }

    /**
     * Return the template of parameter
     *
     * @param \ReflectionMethod $reflectionMethod
     * @return string
     */
    private static function getParameterTemplate(\ReflectionMethod $reflectionMethod): string
    {
        $template = '';
        $reflectionParameters = $reflectionMethod->getParameters();
        $paramCount = \count($reflectionParameters);
        foreach ($reflectionParameters as $reflectionParameter) {
            $paramCount--;
            // Parameter type
            $type = $reflectionParameter->getType();
            if ($type !== null) {
                $type = $type->__toString();
                $template .= " $type ";
            }

            // Parameter name
            $paramName = $reflectionParameter->getName();
            if ($reflectionParameter->isPassedByReference()) {
                $template .= " &\${$paramName} ";
            } elseif ($reflectionParameter->isVariadic()) {
                $template .= " ...\${$paramName} ";
            } else {
                $template .= " \${$paramName} ";
            }

            // Parameter default value
            if ($reflectionParameter->isOptional() && $reflectionParameter->isVariadic() === false) {
                $template .= self::getParameterDefaultValue($reflectionParameter);
            }

            if ($paramCount !== 0) {
                $template .= ',';
            }
        }

        return $template;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @return string
     */
    private static function getPrivatePropertiesTemplate(\ReflectionClass $reflectionClass): string
    {
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        $template = '';
        if ($properties) {
            $defaultProperties = $reflectionClass->getDefaultProperties();
            /** @var \ReflectionProperty $property */
            foreach ($properties as $property) {
                $template .= 'private ';
                $property->isStatic() && $template .= 'static ';
                $template .= '$' . $property->getName();
                isset($defaultProperties[$property->getName()]) && $template .= (' = ' . $defaultProperties[$property->getName()]);
                $template .= ';' . PHP_EOL;
            }
        }
        return $template;
    }

    /**
     * Get default value of parameter
     *
     * @param \ReflectionParameter $reflectionParameter
     * @return string
     */
    private static function getParameterDefaultValue(\ReflectionParameter $reflectionParameter): string
    {
        $template = '';
        $defaultValue = $reflectionParameter->getDefaultValue();
        if ($reflectionParameter->isDefaultValueConstant()) {
            $defaultConst = $reflectionParameter->getDefaultValueConstantName();
            $template = " = {$defaultConst}";
        } elseif (\is_bool($defaultValue)) {
            $value = $defaultValue ? 'true' : 'false';
            $template = " = {$value}";
        } elseif (\is_string($defaultValue)) {
            $template = " = ''";
        } elseif (\is_int($defaultValue)) {
            $template = ' = 0';
        } elseif (\is_array($defaultValue)) {
            $template = ' = []';
        } elseif (\is_float($defaultValue)) {
            $template = ' = []';
        } elseif (\is_object($defaultValue) || null === $defaultValue) {
            $template = ' = null';
        }

        return $template;
    }

    /**
     * @param string $className
     * @param        $reflectionClass
     * @param        $proxyClassName
     * @param        $reflectionMethods
     * @return string
     */
    protected static function previousProxyStrategy(
        string $className,
        $reflectionClass,
        $proxyClassName,
        $reflectionMethods
    ): string {
        $privatePropertiesTemplate = self::getPrivatePropertiesTemplate($reflectionClass);

        // Base class template
        $template = "class $proxyClassName extends $className {
        
            use \\Swoft\\Aop\\AopTrait;
            
            $privatePropertiesTemplate
            
            public function getOriginalClassName(): string {
                return \"{$className}\";
            }
            
            public function __invokeTarget(string \$method, array \$args)
            {
                return parent::{\$method}(...\$args);
            }
            
        ";

        // Methods
        $template .= self::getMethodsTemplate($reflectionMethods);
        $template .= '}';
        return $template;
    }

    /**
     * @return Parser
     * @throws \RuntimeException
     */
    public static function getParser(): Parser
    {
        if (! self::$parser instanceof Parser) {
            self::$parser = new Parser();
        }
        return self::$parser;
    }

    /**
     * @return PrettyPrinterAbstract
     */
    protected static function getPrinter(): PrettyPrinterAbstract
    {
        if (! self::$printer instanceof PrettyPrinterAbstract) {
            self::$printer = new PrettyPrinter\Standard();
        }
        return self::$printer;
    }
}
