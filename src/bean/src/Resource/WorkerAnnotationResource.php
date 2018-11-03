<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Resource;

use Swoft\Helper\ComponentHelper;

/**
 * {@inheritDoc}
 */
class WorkerAnnotationResource extends AnnotationResource
{
    use CustomComponentsRegister;

    /**
     * {@inheritDoc}
     */
    public function registerNamespace()
    {
        $hostDir = \dirname(__FILE__, 4);
        if (\in_array(\basename($hostDir), ['swoft', 'src'])) {
            // Install via Composer
            $componentDirs = scandir($hostDir);
        } else {
            // Independent
            $componentDirs = ['swoft-framework'];
        }
        foreach ($componentDirs as $component) {
            if ($component === '.' || $component === '..') {
                continue;
            }

            $componentDir = $hostDir . DS . $component;
            $componentCommandDir = $componentDir . DS . 'src';
            if (! is_dir($componentCommandDir)) {
                continue;
            }

            $namespace = ComponentHelper::getComponentNamespace($component, $componentDir);
            $this->componentNamespaces[] = $namespace;

            // Ignore the comoponent of console
            if ($component === $this->consoleName) {
                continue;
            }

            $scanDirs = scandir($componentCommandDir, null);
            foreach ($scanDirs as $dir) {
                if ($dir == '.' || $dir == '..') {
                    continue;
                }
                if (\in_array($dir, $this->serverScan, true)) {
                    continue;
                }
                $scanDir = $componentCommandDir . DS . $dir;

                if (! is_dir($scanDir)) {
                    $this->scanFiles[$namespace][] = $scanDir;
                    continue;
                }
                $scanNamespace = $namespace . '\\' . $dir;
                $this->scanNamespaces[$scanNamespace] = $scanDir;
            }
        }

        $this->registerWorkerNamespace();
    }
}
