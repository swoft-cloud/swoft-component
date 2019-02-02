<?php

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require dirname(__DIR__) . '/vendor/autoload.php';
    // application's vendor
} elseif (file_exists(dirname(__DIR__, 3) . '/autoload.php')) {
    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require dirname(__DIR__, 3) . '/autoload.php';

    // load test psr4 config map
    $componentDir = dirname(__DIR__);
    $composerData = json_decode(file_get_contents($componentDir . '/composer.json'), true);

    foreach ($composerData['autoload-dev']['psr-4'] as $prefix => $dir) {
        $loader->addPsr4($prefix, $componentDir . '/' . $dir);
    }
} else {
    exit('Please run "composer install" to install the dependencies' . PHP_EOL);
}
