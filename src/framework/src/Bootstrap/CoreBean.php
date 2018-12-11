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

use Swoft\App;
use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\Application;
use Swoft\Core\BootBeanInterface;
use Swoft\Core\Config;
use Swoft\Event\EventManager;
use Swoft\Log\Formatter\LineFormatter;
use Swoft\Log\Logger;

/**
 * The corebean of swoft
 *
 * @BootBean
 */
class CoreBean implements BootBeanInterface
{
    public function beans()
    {
        return [
            'config' => [
                'class' => Config::class,
                'properties' => value(function () {
                    $config = new Config();
                    $properties = [];
                    $dir = App::getAlias('@properties');
                    if (is_readable($dir)) {
                        $config->load($dir);
                        $properties = $config->toArray();
                    }

                    return $properties;
                }),
            ],
            'application' => [
                'class' => Application::class,
            ],
            'eventManager' => [
                'class' => EventManager::class,
            ],
            'logger' => [
                'class' => Logger::class,
                'name' => APP_NAME,
                'flushInterval' => 100000,
                'flushRequest' => false,
                'handlers' => [],
            ],
            'lineFormatter' => [
                'class' => LineFormatter::class,
                'format' => '%datetime% [%level_name%] [%channel%] [logid:%logid%] [spanid:%spanid%] %messages%',
                'dateFormat' => 'Y-m-d H:i:s',
            ],
        ];
    }
}
