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

        CLog::info('Annotation is beginning');

        $app = $this->application;

        // Find AutoLoader classes. Parse and collect annotations.
        AnnotationRegister::load([
            'disabledAutoLoaders'  => $app->getDisabledAutoLoaders(),
            'disabledPsr4Prefixes' => $app->getDisabledPsr4Prefixes(),
        ]);

        CLog::info('Annotation is scanned');
        return $this->application->afterAnnotation();
    }
}
