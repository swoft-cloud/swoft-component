<?php

namespace Swoft\Processor;

use Swoft\Annotation\AnnotationRegister;

/**
 * Annotation processor
 * @since 2.0
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

        // TODO ... Get disabled loaders by application
        $disabledLoaders = $this->application->getDisabledAutoLoaders();

        // Parse AutoLoader classes config, collect annotations.
        AnnotationRegister::load();

        return $this->application->afterAnnotation();
    }
}
