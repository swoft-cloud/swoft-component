<?php declare(strict_types=1);


namespace Swoft\Db;


use Swoft\Helper\ComposerJSON;
use function bean;
use PDO;
use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            'db'      => [
                'class'  => Database::class,
                'dsn'    => 'mysql:dbname=dbname;host=127.0.0.1',
                'config' => [
                    // fetch array
                    'fetchMode' => PDO::FETCH_ASSOC,
                ],
            ],
            'db.pool' => [
                'class'    => Pool::class,
                'database' => bean('db'),
            ]
        ];
    }

    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
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
