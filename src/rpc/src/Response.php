<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;

/**
 * Class Response
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Response
{
    use PrototypeTrait;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var Error|null
     */
    private $error;

    /**
     * @param $result
     * @param $error
     *
     * @return Response
     */
    public static function new($result, $error): self
    {
        $instance = self::__instance();

        $instance->result = $result;
        $instance->error  = $error;

        return $instance;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return Error
     */
    public function getError(): ?Error
    {
        return $this->error;
    }
}
