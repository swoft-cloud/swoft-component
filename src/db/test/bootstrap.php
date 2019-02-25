<?php
// Composer autoload
$autoloadFile = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoloadFile)) {
    require_once $autoloadFile;
}

$application = new \Swoft\Test\TestApplication();
$application->setBeanFile(__DIR__ . '/case/bean.php');
$application->run();