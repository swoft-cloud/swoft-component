<?php

namespace Swoft\Task\Bean\Annotation;

/**
 * Scheduled annotation
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class Scheduled
{
    /**
     * @var string
     */
    private $cron;

    /**
     * Bean constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->cron = $values['value'];
        }
        if (isset($values['cron'])) {
            $this->cron = $values['cron'];
        }
    }

    /**
     * @return string
     */
    public function getCron(): string
    {
        return $this->cron;
    }
}
