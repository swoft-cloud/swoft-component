<?php

namespace Swoft\Proxy;

use Swoft\Proxy\Handler\HandlerInterface;
use SwoftTest\Aop\NestBean;

/**
 * Proxy factory
 */
class Proxy
{
    /**
     * Return a proxy instance
     *
     * @param string           $className
     * @param HandlerInterface $handler
     *
     * @return object
     * @throws \ReflectionException
     */
    public static function newProxyInstance(string $className, HandlerInterface $handler)
    {
        $reflectionClass   = new \ReflectionClass($className);
        $reflectionMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED);

        // Proxy property
        $proxyId             = \uniqid('', false);
        $proxyClassName      = \basename(str_replace("\\", '/', $className));
        $proxyClassName      = $proxyClassName . '_' . $proxyId;
        $handlerPropertyName = '__handler_' . $proxyId;

        // Base class template
        $template
            = "class $proxyClassName extends $className {
        
            use \Swoft\Aop\AopTrait;

            private \$$handlerPropertyName;
            public function __construct(\$handler)
            {
                \$this->{$handlerPropertyName} = \$handler;
            }
            
            public function getOriginalClassName(): string {
                return \"{$className}\";
            }
        ";

        // Methods
        $template .= self::getMethodsTemplate($reflectionMethods, $proxyId);
        $template .= '}';

        file_put_contents(alias('@runtime/') . $proxyClassName, $template);
        eval($template);
        $newRc = new \ReflectionClass($proxyClassName);

        return $newRc->newInstance($handler);
    }

    /**
     * Return the template of method
     *
     * @param \ReflectionMethod[] $reflectionMethods
     * @param string              $proxyId
     *
     * @return string
     */
    private static function getMethodsTemplate(array $reflectionMethods, string $proxyId): string
    {
        $template            = '';
        $handlerPropertyName = '__handler_' . $proxyId;
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
                $template   .= " : $returnType";
            }

            if (\in_array($methodName, ['method1', 'method2'])) {
                $template
                    .= "{
                // return \$this->{$handlerPropertyName}->invoke('{$methodName}', func_get_args());
                return \$this->__proxy('{$methodName}', func_get_args());
            }";
            } else {
                // overrided method
                $template
                    .= "{
                return \$this->{$handlerPropertyName}->invoke('{$methodName}', func_get_args());
                return \$this->__proxy('{$methodName}', func_get_args());
            }
            ";
            }
        }

        return $template;
    }

    /**
     * Return the template of parameter
     *
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return string
     */
    private static function getParameterTemplate(\ReflectionMethod $reflectionMethod): string
    {
        $template             = '';
        $reflectionParameters = $reflectionMethod->getParameters();
        $paramCount           = \count($reflectionParameters);
        foreach ($reflectionParameters as $reflectionParameter) {
            $paramCount--;
            // Parameter type
            $type = $reflectionParameter->getType();
            if ($type !== null) {
                $type     = $type->__toString();
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
     * Get default value of parameter
     *
     * @param \ReflectionParameter $reflectionParameter
     *
     * @return string
     */
    private static function getParameterDefaultValue(\ReflectionParameter $reflectionParameter): string
    {
        $template     = '';
        $defaultValue = $reflectionParameter->getDefaultValue();
        if ($reflectionParameter->isDefaultValueConstant()) {
            $defaultConst = $reflectionParameter->getDefaultValueConstantName();
            $template     = " = {$defaultConst}";
        } elseif (\is_bool($defaultValue)) {
            $value    = $defaultValue ? 'true' : 'false';
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
}
