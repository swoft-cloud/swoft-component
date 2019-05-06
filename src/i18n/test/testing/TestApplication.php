<?php declare(strict_types=1);


namespace SwoftTest\I18n\Testing;

/**
 * Class TestApplication
 *
 * @since 2.0
 */
class TestApplication extends \Swoft\Test\TestApplication
{
    /**
     * @return string
     */
    public function getResourcePath(): string
    {
        return __DIR__.'/../resource';
    }
}