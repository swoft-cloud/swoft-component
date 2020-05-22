<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Processor;

use Exception;
use Swoft\Annotation\AnnotationRegister;
use Swoft\Log\Helper\CLog;
use const IN_PHAR;

/**
 * Annotation processor
 *
 * @since 2.0
 */
class AnnotationProcessor extends Processor
{
    /**
     * Handle annotation
     *
     * @return bool
     * @throws Exception
     */
    public function handle(): bool
    {
        if (!$this->application->beforeAnnotation()) {
            CLog::warning('Stop annotation processor by beforeAnnotation return false');
            return false;
        }

        $app = $this->application;

        // Find AutoLoader classes. Parse and collect annotations.
        AnnotationRegister::load([
            'inPhar'               => IN_PHAR,
            'basePath'             => $app->getBasePath(),
            'notifyHandler'        => [$this, 'notifyHandle'],
            // TODO force load framework components: bean, error, event, aop
            'disabledAutoLoaders'  => $app->getDisabledAutoLoaders(),
            'excludedPsr4Prefixes' => $app->getDisabledPsr4Prefixes(),
        ]);

        $stats = AnnotationRegister::getClassStats();

        CLog::info(
            'Annotations is scanned(autoloader %d, annotation %d, parser %d)',
            $stats['autoloader'],
            $stats['annotation'],
            $stats['parser']
        );

        return $this->application->afterAnnotation();
    }

    /**
     * @param string $type
     * @param string $target
     *
     * @see \Swoft\Annotation\Resource\AnnotationResource::load()
     */
    public function notifyHandle(string $type, $target): void
    {
        switch ($type) {
            case 'excludeNs':
                CLog::debug('Exclude namespace %s', $target);
                break;
            case 'noLoaderFile':
                CLog::debug('No autoloader on %s', $target);
                break;
            case 'noLoaderClass':
                CLog::debug('Autoloader class not exist %s', $target);
                break;
            case 'findLoaderClass':
                CLog::debug('Find autoloader %s', $target);
                break;
            case 'disabledLoader':
                CLog::debug('Disable autoloader %s', $target);
                break;
            case 'addLoaderClass':
                CLog::debug('Parse autoloader %s', $target);
                break;
            case 'noExistClass':
                CLog::debug('Skip interface or trait %s', $target);
                break;
        }
    }
}
