<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft;

use Monolog\Formatter\LineFormatter;
use Swoft\Config\Config;
use Swoft\Helper\ComposerJSON;
use Swoft\Log\Handler\FileHandler;
use Swoft\Log\Logger;
use Throwable;
use function alias;
use function bean;
use function dirname;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Core bean definition
     *
     * @return array
     * @throws Throwable
     */
    public function beans(): array
    {
        return [
            'config'             => [
                'class'   => Config::class,
                'path'    => alias('@config'),
                'parsers' => [
                    Config::TYPE_PHP => '${phpParser}'
                ]
            ],
            'lineFormatter'      => [
                'class'      => LineFormatter::class,
                'format'     => '%datetime% [%level_name%] [%channel%] [%event%] [tid:%tid%] [cid:%cid%] [traceid:%traceid%] [spanid:%spanid%] [parentid:%parentid%] %context% %messages%',
                'dateFormat' => 'Y-m-d H:i:s',
            ],
            'noticeHandler'      => [
                'class'     => FileHandler::class,
                'logFile'   => '@runtime/logs/notice.log',
                'formatter' => bean('lineFormatter'),
                'levels'    => 'notice,info,debug,trace',
            ],
            'applicationHandler' => [
                'class'     => FileHandler::class,
                'logFile'   => '@runtime/logs/error.log',
                'formatter' => bean('lineFormatter'),
                'levels'    => 'error,warning',
            ],
            'logger'             => [
                'class'        => Logger::class,
                'flushRequest' => false,
                'enable'       => false,
                'handlers'     => [
                    'application' => bean('applicationHandler'),
                    'notice'      => bean('noticeHandler'),
                ],
            ]
        ];
    }

    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }
}
