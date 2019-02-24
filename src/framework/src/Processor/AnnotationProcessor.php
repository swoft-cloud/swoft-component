<?php

namespace Swoft\Processor;

use Swoft\Annotation\AnnotationRegister;

/**
 * Annotation processor
 */
class AnnotationProcessor extends Processor
{

    /**
     * Handle annotation
     *
     * @return bool
     * @throws \Exception
     */
    public function handle(): bool
    {
        if (!$this->application->beforeAnnotation()) {
            return false;
        }

        // Parse AutoLoader classes config, collect annotations.
        AnnotationRegister::load();

        return $this->application->afterAnnotation();
    }
}
