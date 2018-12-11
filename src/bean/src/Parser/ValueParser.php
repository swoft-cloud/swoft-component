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
namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Value;
use Swoft\Core\Types;
use Swoft\Helper\DocumentHelper;

/**
 * value注解解析器
 *
 * @uses      ValueParser
 * @version   2017年11月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ValueParser extends AbstractParser
{
    private $supportTypes = [
        'bool' => Types::BOOLEAN,
        'boolean' => Types::BOOLEAN,
        'string' => Types::STRING,
        'int' => Types::INTEGER,
        'integer' => Types::INTEGER,
        'float' => Types::FLOAT,
        'double' => Types::FLOAT,
        'array' => Types::ARRAY,
    ];

    /**
     * Inject注解解析
     *
     * @param string $className
     * @param Value  $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     * @return array
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        $injectValue = $objectAnnotation->getName();
        $envValue = $objectAnnotation->getEnv();

        if (empty($injectValue) && empty($envValue)) {
            throw new \InvalidArgumentException("the name and env of @Value can't be empty! class={$className} property={$propertyName}");
        }

        $isRef = false;
        $injectProperty = null;
        if (!empty($injectValue)) {
            list($injectProperty, $isRef) = $this->annotationResource->getTransferProperty($injectValue);
        }

        if (!empty($envValue)) {
            $value = $this->getEnvValue($envValue);
            $type = $this->getPropertyType($className, $propertyName);
            $value = $this->getTransferEnvValue($value, $type);
            $injectProperty = ($value !== null) ? $value : $injectProperty;
            $isRef = ($value !== null) ? false : $isRef;
        }

        return [$injectProperty, $isRef];
    }

    /**
     * transfer the value of env
     *
     * @param mixed $value
     * @param bool  $isArray
     *
     * @return mixed
     */
    private function getTransferEnvValue($value, $type)
    {
        if ($value === null) {
            return null;
        }

        switch ($type) {
            case Types::BOOLEAN:
                $value = (bool)$value;
                break;
            case Types::STRING:
                $value = (string)$value;
                break;
            case Types::INTEGER:
                $value = (int)$value;
                break;
            case Types::FLOAT:
                $value = (float)$value;
                break;
            case Types::ARRAY:
                if (empty($value)) {
                    $value = [];
                } else {
                    $value = explode(',', $value);
                }
                break;
            default:
                break;
        }

        return $value;
    }

    /**
     * whether the value of env is array
     *
     * @param string $className
     * @param string $propertyName
     *
     * @return bool
     */
    private function isEnvArrayValue(string $className, string $propertyName)
    {
        $rc = new \ReflectionClass($className);
        $rp = $rc->getProperty($propertyName);
        $doc = $rp->getDocComment();
        $tags = DocumentHelper::tagList($doc);
        if (isset($tags['var']) && $tags['var'] == 'array') {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    private function getPropertyType(string $className, string $propertyName): int
    {
        $rc = new \ReflectionClass($className);
        $rp = $rc->getProperty($propertyName);
        $doc = $rp->getDocComment();
        $tags = DocumentHelper::tagList($doc);
        if (isset($tags['var']) && array_key_exists($tags['var'], $this->supportTypes)) {
            return $this->supportTypes[$tags['var']];
        }

        return Types::UNKNOWN;
    }

    /**
     * match env value
     *
     * @param string $envValue
     *
     * @return mixed|string
     */
    private function getEnvValue(string $envValue)
    {
        $value = $envValue;
        if (preg_match('/^\$\{(.*)\}$/', $envValue, $match)) {
            $value = env($match[1]);
        }

        return $value;
    }
}
