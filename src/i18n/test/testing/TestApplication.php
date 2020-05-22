<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\I18n\Testing;

/**
 * Class TestApplication
 *
 * @since 2.0
 */
class TestApplication extends \SwoftTest\Testing\TestApplication
{
    /**
     * @return string
     */
    public function getResourcePath(): string
    {
        return __DIR__.'/../resource';
    }
}
