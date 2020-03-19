<?php declare(strict_types=1);
/**
 * @var ClassLoader $loader
 */

use Composer\Autoload\ClassLoader;
use SwoftTest\Testing\TestApplication;

$componentDir = dirname(__DIR__);
if (file_exists($componentDir . '/vendor/autoload.php')) {
    /** @noinspection PhpIncludeInspection */
    $loader = require $componentDir . '/vendor/autoload.php';
    // application's vendor
} elseif (file_exists(dirname(__DIR__, 3) . '/autoload.php')) {
    /** @noinspection PhpIncludeInspection */
    $loader = require dirname(__DIR__, 3) . '/autoload.php';

    // need load test psr4 config map
    $jsonFile = $componentDir . '/composer.json';
    $jsonData = json_decode(file_get_contents($jsonFile), true);

    foreach ($jsonData['autoload-dev']['psr-4'] as $prefix => $dir) {
        $loader->addPsr4($prefix, $componentDir . '/' . $dir);
    }
} else {
    exit('Please run "composer install" to install the dependencies' . PHP_EOL);
}

// for local new package
// $loader->addPsr4("Swoft\\Serialize\\", 'vendor/swoft/serialize/src/');

// if is from ../bin/swoft
if (defined('RUN_TEST_APP') && !RUN_TEST_APP) {
    return;
}

$app = new TestApplication(__DIR__);
$app->run();
