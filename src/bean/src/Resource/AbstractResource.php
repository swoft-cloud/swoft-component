<?php

namespace Swoft\Bean\Resource;

use Swoft\Bean\Exception\InvalidArgumentException;
use function count;
use function explode;
use function is_string;
use function preg_match;
use function str_replace;
use function strpos;

abstract class AbstractResource implements ResourceInterface
{
    /**
     * properties.php配置
     */
    protected $properties = [];

    /**
     * 非数组格式属性值转换
     *
     * @param mixed $property
     */
    public function getTransferProperty($property): array
    {
        if (! is_string($property)) {
            return [$property, 0];
        }

        $injectProperty = $property;
        $isRef = preg_match('/^\$\{(.*)\}$/', $property, $match);

        // 解析
        if (! empty($match)) {
            $isRef = strpos($match[1], 'config') === 0 ? 0 : $isRef;
            $injectProperty = $this->getInjectProperty($match[1]);
        }

        return [$injectProperty, $isRef];
    }

    /**
     * 属性值引用解析
     *
     * @param string $property
     * @return mixed|string
     */
    public function getInjectProperty(string $property)
    {
        // '${beanName}'格式解析
        $propertyKeys = explode('.', $property);
        if (count($propertyKeys) === 1) {
            return $property;
        }

        if ($propertyKeys[0] !== 'config') {
            throw new InvalidArgumentException('properties配置引用格式不正确，key=' . $propertyKeys[0]);
        }

        // '${config.xx.yy}' 格式解析,直接key
        $propertyKey = str_replace('config.', '', $property);
        if (isset($this->properties[$propertyKey])) {
            return $this->properties[$propertyKey];
        }

        // '${config.xx.yy}' 格式解析, 层级解析
        unset($propertyKeys[0]);
        $layerProperty = empty($propertyKeys) ? null : $this->properties;
        foreach ($propertyKeys as $subPropertyKey) {
            if (isset($layerProperty[$subPropertyKey])) {
                $layerProperty = $layerProperty[$subPropertyKey];
                continue;
            }

            $layerProperty = null;
            break;
        }

        return $layerProperty;
    }
}
