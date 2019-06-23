<?php declare(strict_types=1);


namespace Swoft\Bean;

use Swoft\Bean\Exception\BeanException;

/**
 * Class InterfaceRegister
 *
 * @since 2.0
 */
class InterfaceRegister
{
    /**
     * @var array
     *
     * @example
     * [
     *     'interfaceName' => [
     *          'className'
     *      ]
     * ]
     */
    private static $primaryInterface;

    /**
     * @var array
     *
     * @example
     * [
     *      'interfaceName' => [
     *          'className' => 'beanName'
     *      ]
     * ]
     */
    private static $interfaces;

    public static function registerPrimary(string $interfaceClass, string $className): void
    {

    }

    /**
     * @param string $interfaceClass
     * @param string $className
     * @param string $beanName
     */
    public static function registerInterface(string $interfaceClass, string $className, string $beanName): void
    {
        self::$interfaces[$interfaceClass][$className] = $beanName;
    }

    /**
     * @param string $interfaceClass
     *
     * @return string
     * @throws BeanException
     */
    public static function getInterfaceInjectBean(string $interfaceClass): string
    {
        $primaryClass = self::$primaryInterface[$interfaceClass] ?? '';
        if (!empty($primaryClass)) {
            $beanName = self::$interfaces[$interfaceClass][$primaryClass] ?? '';
            $beanName = empty($beanName) ? $primaryClass : $beanName;
            return $beanName;
        }

        $classNames = self::$interfaces[$interfaceClass] ?? [];
        if (empty($classNames)) {
            throw new BeanException(
                sprintf('Interface(%s) has not inject instance!', $interfaceClass)
            );
        }

        $beanName = current($classNames);
        $beanName = empty($beanName) ? key($classNames) : $beanName;
        return $beanName;
    }
}