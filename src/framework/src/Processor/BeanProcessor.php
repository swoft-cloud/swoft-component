<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Processor;

use InvalidArgumentException;
use ReflectionException;
use Swoft\Annotation\AnnotationRegister;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\BeanFactory;
use Swoft\BeanHandler;
use Swoft\Config\Config;
use Swoft\Contract\DefinitionInterface;
use Swoft\Helper\SwoftHelper;
use Swoft\Log\Helper\CLog;
use Swoft\Stdlib\Helper\ArrayHelper;
use function alias;
use function file_exists;
use function get_class;
use function sprintf;

/**
 * Class BeanProcessor
 *
 * @since 2.0
 */
class BeanProcessor extends Processor
{
    /**
     * Handle bean
     *
     * @return bool
     * @throws ReflectionException
     * @throws AnnotationException
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

        $stats = BeanFactory::getStats();
        CLog::info('Bean is initialized(%s)', SwoftHelper::formatStats($stats));

        /* @var Config $config */
        $config = BeanFactory::getBean('config');
        CLog::info('Config path is %s', $config->getPath());

        if ($configEnv = $config->getEnv()) {
            CLog::info('Config env=%s', $configEnv);
        } else {
            CLog::info('Config env is not setting');
        }

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

        // get disabled loaders by application
        $disabledLoaders = $this->application->getDisabledAutoLoaders();

        foreach ($autoLoaders as $autoLoader) {
            if (!$autoLoader instanceof DefinitionInterface) {
                continue;
            }

            $loaderClass = get_class($autoLoader);

            // If the component is disabled by app.
            if (isset($disabledLoaders[$loaderClass])) {
                CLog::info('Auto loader(%s) is <cyan>DISABLED</cyan>, skip handle it', $loaderClass);
                continue;
            }

            // If the component is disabled by self.
            if (!$autoLoader->isEnable()) {
                CLog::info('Auto loader(%s) is <cyan>DISABLED</cyan>, skip handle it', $loaderClass);
                continue;
            }

            $definitions = ArrayHelper::merge($definitions, $autoLoader->beans());
        }

        // Application bean definitions
        $beanFile = alias($this->application->getBeanFile());

        if (!file_exists($beanFile)) {
            throw new InvalidArgumentException(sprintf('The bean config file of %s is not exist!', $beanFile));
        }

        /** @noinspection PhpIncludeInspection */
        $beanDefinitions = require $beanFile;

        return ArrayHelper::merge($definitions, $beanDefinitions);
    }
}
