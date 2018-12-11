<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Http\Message\Testing\Web;

use Swoft\App;

class Request extends \Swoft\Http\Message\Server\Request
{
    public function __construct($method, $uri, array $headers = [], $body = null, $version = '1.1')
    {
        if (!App::$isInTest) {
            throw new \RuntimeException(sprintf('Is not available to use %s in non testing enviroment', __CLASS__));
        }
        parent::__construct($method, $uri, $headers, $body, $version);
    }
}
