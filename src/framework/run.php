<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use Swoole\ExitException;

Co::set([
    'log_level'   => SWOOLE_LOG_INFO,
    'trace_flags' => 0
]);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
if (version_compare('7.1.0', PHP_VERSION, '>')) {
    fwrite(
        STDERR,
        sprintf(
            'This version of PHPUnit is supported on PHP 7.1 and PHP 7.2.' . PHP_EOL . 'You are using PHP %s (%s).' . PHP_EOL,
            PHP_VERSION,
            PHP_BINARY
        )
    );
    die(1);
}
if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}
foreach ([
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php'
] as $file) {
    if (file_exists($file)) {
        define('PHPUNIT_COMPOSER_INSTALL', $file);
        break;
    }
}
unset($file);
if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
    $tips = <<<TXT
You need to set up the project dependencies using Composer:
    composer install
You can learn all about Composer on https://getcomposer.org/
TXT;

    fwrite(STDERR, $tips . PHP_EOL);
    die(1);
}

if (!in_array('-c', $_SERVER['argv'])) {
    $_SERVER['argv'][] = '-c';
    $_SERVER['argv'][] = __DIR__ . '/phpunit.xml';
}

require PHPUNIT_COMPOSER_INSTALL;

$status = 0;
srun(function () {
    // Status
    global $status;

    try {
        $status = PHPUnit\TextUI\Command::main(false);
    } catch (ExitException $e) {
        var_dump($e->getMessage());
        $status = $e->getCode();
    }
});


exit($status);
