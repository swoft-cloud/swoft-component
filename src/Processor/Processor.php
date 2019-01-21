<?php

namespace Swoft\Processor;

use Swoft\ApplicationInterface;
use Swoft\SwoftInterface;

/**
 * Abstract processor
 */
abstract class Processor implements ProcessorInterface
{
    /**
     * Swoft application
     *
     * @var SwoftInterface
     */
    protected $application;

    /**
     * Processor constructor.
     */
    public function __construct(SwoftInterface $application)
    {
        $this->application = $application;
    }
}