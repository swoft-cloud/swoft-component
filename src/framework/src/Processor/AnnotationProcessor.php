<?php

namespace Swoft\Processor;

use Swoft\Annotation\AnnotationRegister;
use Swoft\Log\Helper\CLog;

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

        CLog::info('Annotation load is beginning');

        $app = $this->application;

        // Find AutoLoader classes. Parse and collect annotations.
        AnnotationRegister::load([
            'basePath'             => $app->getBasePath(),
            'disabledAutoLoaders'  => $app->getDisabledAutoLoaders(),
            'disabledPsr4Prefixes' => $app->getDisabledPsr4Prefixes(),
        ]);

        $stats = AnnotationRegister::getClassStats();

        CLog::info(
            'Annotation is scanned (autoloader %d, annotation %d, parser %d,)',
            $stats['annotation'],
            $stats['parser'],
            $stats['autoloader']
        );

        return $this->application->afterAnnotation();
    }
}
