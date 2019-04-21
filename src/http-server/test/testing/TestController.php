<?php declare(strict_types=1);

namespace SwoftTest\Http\Server\Testing;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class TestController
 *
 * @Controller("fixture/test")
 */
class TestController
{
    /**
     * @RequestMapping()
     * @return string
     */
    public function hello(): string
    {
        return 'hello';
    }
}
