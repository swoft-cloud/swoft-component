<?php

namespace Swoft\Processor;

use Swoft\Annotation\AnnotationRegister;
use Swoft\Helper\CLog;

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
            CLog::warning('Stop annotation processor by beforeAnnotation return false');
            return false;
        }

        // TODO ... Get disabled loaders by application
        $disabledLoaders = $this->application->getDisabledAutoLoaders();

        CLog::info('Annotation is beginning');

        // Parse AutoLoader classes config, collect annotations.
        AnnotationRegister::load();

        CLog::info('Annotation is scanned');
        return $this->application->afterAnnotation();
    }
}
