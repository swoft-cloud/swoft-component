<?php

namespace Swoft\Task\Event\Events;

use Swoft\Event\Event;

/**
 * 任务后置事件源
 */
class AfterTaskEvent extends Event
{
    /**
     * 日志ID
     *
     * @var string
     */
    private $logid;

    /**
     * 跨度ID
     *
     * @var int
     */
    private $spanid;

    /**
     * 任务名称
     *
     * @var string
     */
    private $taskName;

    /**
     * 方法
     *
     * @var string
     */
    private $method;

    /**
     * 任务类型
     *
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $taskClass;

    public function __construct(
        $name = null,
        string $logid,
        int $spanid,
        string $taskName,
        string $method,
        string $type,
        string $taskClass
    ) {
        parent::__construct($name);

        $this->type = $type;
        $this->logid = $logid;
        $this->spanid = $spanid;
        $this->method = $method;
        $this->taskName = $taskName;
        $this->taskClass = $taskClass;
    }

    public function getLogid(): string
    {
        return $this->logid;
    }

    public function getSpanid(): int
    {
        return $this->spanid;
    }

    public function getTaskName(): string
    {
        return $this->taskName;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTaskClass(): string
    {
        return $this->taskClass;
    }
}
