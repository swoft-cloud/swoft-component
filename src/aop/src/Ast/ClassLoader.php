<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Ast;

use Composer\Autoload\ClassLoader as ComposerClassLoader;
use Swoft\Helper\StringHelper;

/**
 * Class ClassLoader
 *
 * @author  huangzhhui <h@swoft.com>
 * @package Swoft\Aop\Ast
 */
class ClassLoader
{
    /**
     * @var ComposerClassLoader
     */
    protected $loader;

    /**
     * ClassLoader constructor.
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->setLoader($this->getComposerLoader());
    }

    /**
     * @param $class
     * @return string
     */
    public function getFileByClassName($class): string
    {
        return (string)$this->getLoader()->findFile($class);
    }

    /**
     * @return ComposerClassLoader
     */
    public function getLoader(): ComposerClassLoader
    {
        return $this->loader;
    }

    /**
     * @param ComposerClassLoader $loader
     * @return ClassLoader
     */
    public function setLoader(ComposerClassLoader $loader): ClassLoader
    {
        $this->loader = $loader;
        return $this;
    }

    /**
     * @return ComposerClassLoader
     * @throws \RuntimeException
     */
    protected function getComposerLoader(): ComposerClassLoader
    {
        foreach (get_declared_classes() as $class) {
            if (StringHelper::startsWith($class, 'ComposerAutoloaderInit')) {
                $composerAutoloader = $class;
                break;
            }
        }
        if (! $composerAutoloader) {
            throw new \RuntimeException('No ComposerAutoloaderInit found');
        }
        return $composerAutoloader::getLoader();
    }
}
