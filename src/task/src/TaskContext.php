<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;

/**
 * Class TaskContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class TaskContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return TaskContext
     */
    public static function new(Request $request, Response $response): self
    {
        $instance = self::__instance();

        $instance->request  = $request;
        $instance->response = $response;

        $instance->setMulti($request->getExt());
        return $instance;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return int
     */
    public function getTaskId(): int
    {
        return $this->request->getTaskId();
    }

    /**
     * @return string
     */
    public function getTaskUniqId(): string
    {
        return $this->request->getTaskUniqid();
    }

    /**
     * Clear
     */
    public function clear(): void
    {
        $this->request  = null;
        $this->response = null;
    }
}
