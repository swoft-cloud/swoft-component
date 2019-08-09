<?php

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require dirname(__DIR__) . '/vendor/autoload.php';
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

$libDir = __DIR__ . '/';
$npMap  = [
    'SwoftTool\\'     => $libDir,
    // 'Inhere\\ValidateTest\\' => $libDir . '/test/',
];

spl_autoload_register(function ($class) use ($npMap) {
    foreach ($npMap as $np => $dir) {
        if (strpos($class, $np) !== 0) {
            continue;
        }

        $file = $dir . str_replace('\\', '/', substr($class, strlen($np))) . '.php';

        if (file_exists($file)) {
            include $file;
        }
    }
});
