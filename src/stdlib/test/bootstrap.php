<?php declare(strict_types=1);

// vendor at package dir
$packagePath = dirname(__DIR__);
if (file_exists($packagePath . '/vendor/autoload.php')) {
    /** @noinspection PhpIncludeInspection */
    require $packagePath . '/vendor/autoload.php';
} else {
    $componentDir = dirname(__DIR__, 3);
    $loaderFiles  = [
        // vendor at swoft/component dir
        $componentDir . '/vendor/autoload.php',
        // application's vendor
        dirname(__DIR__, 5) . '/autoload.php',
    ];

    /** @var Composer\Autoload\ClassLoader $loader */
    $found = false;
    foreach ($loaderFiles as $loaderFile) {
        if (file_exists($loaderFile)) {
            /** @noinspection PhpIncludeInspection */
            $loader = require $loaderFile;
            $found  = true;
            break;
        }
    }

    if (!$found) {
        exit('Please run "composer install" to install the dependencies' . PHP_EOL);
    }

    // need load testing psr4 config map
    $jsonFile = $componentDir . '/composer.json';
    $jsonData = json_decode(file_get_contents($jsonFile), true);
    foreach ($jsonData['autoload-dev']['psr-4'] as $prefix => $dir) {
        $loader->addPsr4($prefix, $componentDir . '/' . $dir);
    }
}
