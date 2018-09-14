<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Resource;

use InvalidArgumentException;
use Swoft\Bean\ObjectDefinition;
use Swoft\Bean\ObjectDefinition\ArgsInjection;
use Swoft\Bean\ObjectDefinition\MethodInjection;
use Swoft\Bean\ObjectDefinition\PropertyInjection;
use function is_array;

/**
 * 定义配置解析资源
 */
class DefinitionResource extends AbstractResource
{
    /**
     * 定义的beans配置
     *
     * @var array
     */
    private $definitions;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
        $this->properties = $definitions['config']['properties'] ?? [];
    }

    /**
     * 获取已解析的配置beans
     *
     * @return array
     * [
     *     'beanName' => ObjectDefinition,
     *      ...
     * ]
     */
    public function getDefinitions(): array
    {
        $definitions = [];
        foreach ($this->definitions as $beanName => $definition) {
            $definitions[$beanName] = $this->resolveDefinitation($beanName, $definition);
        }

        return $definitions;
    }

    /**
     * 解析bean配置
     */
    public function resolveDefinitation(string $beanName, array $definition): ObjectDefinition
    {
        if (! isset($definition['class'])) {
            throw new InvalidArgumentException('definitions of bean 初始化失败，class字段没有配置,Data=' . json_encode($definition));
        }

        $className = $definition['class'];
        unset($definition['class']);

        // 初始化
        $objDefinitation = new ObjectDefinition();
        $objDefinitation->setName($beanName);
        $objDefinitation->setClassName($className);

        // 解析属性和构造函数
        list($propertyInjections, $constructorInjection) = $this->resolverPropertiesAndConstructor($definition);

        // 设置属性和构造函数
        $objDefinitation->setPropertyInjections($propertyInjections);
        if ($constructorInjection !== null) {
            $objDefinitation->setConstructorInjection($constructorInjection);
        }

        return $objDefinitation;
    }

    /**
     * 解析配置属性和构造函数
     *
     * @return array [$propertyInjections, $constructorInjection]
     */
    private function resolverPropertiesAndConstructor(array $definition): array
    {
        $propertyInjections = [];
        $constructorInjection = null;

        // 循环解析
        foreach ($definition as $name => $property) {
            // 构造函数
            if (is_array($property) && $name === 0) {
                $constructorInjection = $this->resolverConstructor($property);
                continue;
            }

            // 数组属性解析
            if (is_array($property)) {
                $injectProperty = $this->resolverArrayArgs($property);
                $propertyInjection = new PropertyInjection($name, $injectProperty, false);
                $propertyInjections[$name] = $propertyInjection;
                continue;
            }

            // 普通解析
            list($injectProperty, $isRef) = $this->getTransferProperty($property);
            $propertyInjection = new PropertyInjection($name, $injectProperty, (bool)$isRef);
            $propertyInjections[$name] = $propertyInjection;
        }

        return [$propertyInjections, $constructorInjection];
    }

    /**
     * 解析数组值属性
     */
    private function resolverArrayArgs(array $propertyValue): array
    {
        $args = [];
        foreach ($propertyValue as $key => $subArg) {
            // 递归解析
            if (is_array($subArg)) {
                $args[$key] = $this->resolverArrayArgs($subArg);
                continue;
            }

            // 普通解析
            list($injectProperty, $isRef) = $this->getTransferProperty($subArg);
            $args[$key] = new ArgsInjection($injectProperty, (bool)$isRef);
        }

        return $args;
    }

    /**
     * 解析构造函数
     */
    private function resolverConstructor(array $args): MethodInjection
    {
        $methodArgs = [];
        foreach ($args as $arg) {
            // 数组参数解析
            if (is_array($arg)) {
                $injectProperty = $this->resolverArrayArgs($arg);
                $methodArgs[] = new ArgsInjection($injectProperty, false);
                continue;
            }

            // 普通参数解析
            list($injectProperty, $isRef) = $this->getTransferProperty($arg);
            $methodArgs[] = new ArgsInjection($injectProperty, (bool)$isRef);
        }

        $methodInject = new MethodInjection('__construct', $methodArgs);
        return $methodInject;
    }
}
