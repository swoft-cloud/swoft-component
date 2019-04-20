<?php declare(strict_types=1);


namespace SwoftTest\Http\Server\Testing\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class RequestController
 *
 * @since 2.0
 *
 * @Controller("request")
 */
class RequestController
{
    /**
     * @RequestMapping("common")
     */
    public function common()
    {
        return ['common'];
    }
}