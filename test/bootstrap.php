<?php

use SwoftTest\Component\Testing\TestApplication;

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    $loader = require dirname(__DIR__) . '/vendor/autoload.php';
    // application's vendor
} elseif (file_exists(dirname(__DIR__, 3) . '/autoload.php')) {
    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require dirname(__DIR__, 3) . '/autoload.php';

    // need load test psr4 config map
    $componentDir  = dirname(__DIR__);
    $componentJson = $componentDir . '/composer.json';
    $composerData  = json_decode(file_get_contents($componentJson), true);

    foreach ($composerData['autoload-dev']['psr-4'] as $prefix => $dir) {
        $loader->addPsr4($prefix, $componentDir . '/' . $dir);
    }
} else {
    exit('Please run "composer install" to install the dependencies' . PHP_EOL);
}

/** @var \Composer\Autoload\ClassLoader $loader */
$loader->addPsr4("Swoft\\Cache\\", 'vendor/swoft/cache/src/');
$loader->addPsr4("Swoft\\Swlib\\", 'vendor/swoft/swlib/src/');
$loader->addPsr4("Swoft\\Serialize\\", 'vendor/swoft/serialize/src/');

// if is from ../bin/swoft
if (defined('RUN_TEST_APP') && !RUN_TEST_APP) {
    return;
}

$app = new TestApplication([
    'beanFile'            => dirname(__DIR__) . '/testing/bean.php',
    'disabledAutoLoaders' => [
        \App\AutoLoader::class => 1,
    ],
]);
$app->run();
