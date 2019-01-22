<?php declare(strict_types=1);


namespace Swoft;


use Swoft\Aop\Proxy;
use Swoft\Bean\Definition\ObjectDefinition;
use Swoft\Bean\Handler;

/**
 * Class BeanHandler
 *
 * @since 2.0
 */
class BeanHandler extends Handler
{
    /**
     * Before initialize bean
     *
     * @param string           $beanName
     * @param string           $className
     * @param ObjectDefinition $objDfn
     * @param array            $annotation
     */
    public function beforeInit(string $beanName, string $className, ObjectDefinition $objDfn, array $annotation): void
    {

    }

    /**
     * Class proxy
     *
     * @param string $className
     *
     * @return string
     */
    public function classProxy(string $className): string
    {
        return Proxy::newClassName($className);
    }

    /**
     * Get reference value
     *
     * @param $value
     *
     * @return mixed
     */
    public function getReferenceValue($value)
    {
        // Remove `config.`
        $values = explode('.', $value);
        array_shift($values);
        $value = implode('.', $values);

        return config($value);
    }
}