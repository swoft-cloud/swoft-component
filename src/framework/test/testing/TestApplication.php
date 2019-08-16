<?php declare(strict_types=1);

namespace SwoftTest\Testing;

use Swoft\Processor\AnnotationProcessor;
use Swoft\Processor\BeanProcessor;
use Swoft\Processor\ConfigProcessor;
use Swoft\Processor\EnvProcessor;
use Swoft\Processor\EventProcessor;
use Swoft\SwoftApplication;

/**
 * Class TestApplication
 *
 * @since 2.0
 */
class TestApplication extends SwoftApplication
{
    public function getCLoggerConfig(): array
    {
        $config = parent::getCLoggerConfig();

        // Dont print log to terminal
        $config['enable'] = false;

        return $config;
    }

    /**
     * Rewrite processors
     *
     * @return array
     */
    protected function processors(): array
    {
        return [
            new EnvProcessor($this),
            new ConfigProcessor($this),
            new AnnotationProcessor($this),
            new BeanProcessor($this),
            new EventProcessor($this)
        ];
    }
}
