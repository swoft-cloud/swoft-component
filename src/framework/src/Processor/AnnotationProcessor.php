<?php

namespace Swoft\Processor;

use Composer\Autoload\ClassLoader;
use Swoft\Annotation\AnnotationRegister;

/**
 * Annotation processor
 */
class AnnotationProcessor extends Processor
{
    /**
     * Handle annotation
     */
    public function handle(): bool
    {
        if (!$this->application->beforeAnnotation()) {
            return false;
        }

        AnnotationRegister::load();

        return $this->application->afterAnnotation();
    }
}