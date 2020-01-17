<?php declare(strict_types=1);

namespace SwoftTest\Testing;

use Swoft\SwoftApplication;

/**
 * Class TestApplication
 *
 * @since 2.0
 */
class TestApplication extends SwoftApplication
{
    public function __construct(array $config = [])
    {
        // tests: disable run console application
        $this->setStartConsole(false);

        parent::__construct($config);
    }

    public function getCLoggerConfig(): array
    {
        $config = parent::getCLoggerConfig();

        // Dont print log to terminal
        $config['enable'] = false;

        return $config;
    }
}
