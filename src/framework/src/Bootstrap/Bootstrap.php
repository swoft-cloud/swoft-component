<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bootstrap;

use InvalidArgumentException;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Collector\BootstrapCollector;
use Swoft\Bootstrap\Boots\Bootable;
use function array_column;
use function array_multisort;
use function define;

/**
 * @Bean
 */
class Bootstrap implements Bootable
{
    /**
     * @throws \InvalidArgumentException
     */
    public function bootstrap()
    {
        // Define Swoft version
        define('SWOFT_VERSION', '1.1.0');

        // Load bootstrap
        $bootstraps = BootstrapCollector::getCollector();
        $temp = array_column($bootstraps, 'order');

        array_multisort($temp, SORT_ASC, $bootstraps);

        foreach ($bootstraps as $beanName => $name) {
            /* @var Bootable $bootstrap */
            $bootstrap = App::getBean($beanName);
            if (! $bootstrap instanceof Bootable) {
                throw new InvalidArgumentException(sprintf('Bootstrap %s have to implement %s', $beanName, Bootable::class));
            }
            $bootstrap->bootstrap();
        }
    }
}
