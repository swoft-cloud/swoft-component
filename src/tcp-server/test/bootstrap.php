<?php

use Composer\Autoload\ClassLoader;
use Swoft\Stdlib\Helper\Sys;
use SwoftTest\Testing\TestApplication;
use Swoole\Process;

// current component dir
$componentDir  = dirname(__DIR__, 3);
$componentJson = $componentDir . '/composer.json';

// vendor at component dir
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require dirname(__DIR__) . '/vendor/autoload.php';
} elseif (file_exists(dirname(__DIR__, 3) . '/vendor/autoload.php')) {
    /** @var ClassLoader $loader */
    $loader = require dirname(__DIR__, 3) . '/vendor/autoload.php';

    // need load testing psr4 config map
    $composerData = json_decode(file_get_contents($componentJson), true);
    foreach ($composerData['autoload-dev']['psr-4'] as $prefix => $dir) {
        $loader->addPsr4($prefix, $componentDir . '/' . $dir);
    }

    // application's vendor
} elseif (file_exists(dirname(__DIR__, 5) . '/autoload.php')) {
    /** @var ClassLoader $loader */
    $loader = require dirname(__DIR__, 5) . '/autoload.php';

    // need load testing psr4 config map
    $composerData = json_decode(file_get_contents($componentJson), true);

    foreach ($composerData['autoload-dev']['psr-4'] as $prefix => $dir) {
        $loader->addPsr4($prefix, $componentDir . '/' . $dir);
    }
} else {
    exit('Please run "composer install" to install the dependencies' . PHP_EOL);
}

// php run.php -c src/tcp-server/phpunit.xml
// SWOFT_TEST_TCP_SERVER=1
if (1 === (int)getenv('SWOFT_TEST_TCP_SERVER')) {
    // Output: "php is /usr/local/bin/php"
    [$ok, $ret,] = Sys::run('type php');
    if (0 !== $ok) {
        exit('php not found');
    }

    $type = 'tcp';
    $php  = substr(trim($ret), 7);
    $proc = new Process(function (Process $proc) use ($php, $type) {
        // $proc->exec($php, [ $dir . '/test/bin/swoft', 'ws:start');
        $proc->exec($php, ['test/bin/swoft', $type . ':start']);
    });
    $pid  = $proc->start();
    echo "Swoft test server started, PID $pid\n";

    // wait server starting...
    sleep(2);
    echo file_get_contents('http://127.0.0.1:28308/hi');
}

$application = new TestApplication([
    'basePath' => __DIR__
]);
$application->setBeanFile(__DIR__ . '/testing/bean.php');
$application->run();

if (isset($pid) && $pid > 0) {
    echo "Stop server on tests end. PID $pid";
    $ok = Process::kill($pid, 15);
    echo $ok ? " OK\n" : " FAIL\n";
}
