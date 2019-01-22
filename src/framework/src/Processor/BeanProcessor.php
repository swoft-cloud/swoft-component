<?php

namespace Swoft\Processor;

use http\Exception\InvalidArgumentException;
use Swoft\Annotation\AnnotationRegister;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\BeanHandler;
use Swoft\DefinitionInterface;
use Swoft\Proxy\BeanInitialize;
use Swoft\Proxy\BeanProxy;
use Swoft\Reference\ConfigReference;
use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Bean processor
 */
class BeanProcessor extends Processor
{
    /**
     * Handle bean
     */
    public function handle(): bool
    {
        if (!$this->application->beforeBean()) {
            return false;
        }

        $handler     = new BeanHandler();
        $definitions = $this->getDefinitions();
        $parsers     = AnnotationRegister::getParsers();
        $annotations = AnnotationRegister::getAnnotations();

        BeanFactory::addDefinitions($definitions);
        BeanFactory::addAnnotations($annotations);
        BeanFactory::addParsers($parsers);
        BeanFactory::setHandler($handler);
        BeanFactory::init();

        return $this->application->afterBean();
    }

    /**
     * Get bean definitions
     *
     * @return array
     */
    private function getDefinitions(): array
    {
        // Core beans
        $definitions = [];
        $autoLoaders = AnnotationRegister::getAutoLoaders();
        foreach ($autoLoaders as $autoLoader) {
            if ($autoLoader instanceof DefinitionInterface) {
                $definitions = ArrayHelper::merge($definitions, $autoLoader->coreBean());
            }
        }

        // Bean definitions
        $beanFile = $this->application->getBeanFile();
        $beanFile = alias($beanFile);

        if (!file_exists($beanFile)) {
            throw new \InvalidArgumentException(sprintf('The file of %s is not exist!', $beanFile));
        }

        $beanDefinitions = require $beanFile;
        $definitions     = ArrayHelper::merge($definitions, $beanDefinitions);

        return $definitions;
    }
}