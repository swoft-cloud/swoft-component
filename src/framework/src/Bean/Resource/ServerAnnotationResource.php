<?php

namespace Swoft\Bean\Resource;

use Swoft\Helper\ComponentHelper;

/**
 * The annotation resource of server
 */
class ServerAnnotationResource extends AnnotationResource
{
    /**
     * Register the scaned namespace
     */
    public function registerNamespace()
    {
        $hostDir = \dirname(__FILE__, 5);
        if (\in_array(\basename($hostDir), ['swoft', 'src'])) {
            //install by composer
            $componentDirs = scandir($hostDir, null);
        } else {
            //independent
            $componentDirs = ['swoft-framework'];
        }
        foreach ($componentDirs as $component) {
            if ($component == '.' || $component == '..') {
                continue;
            }

            $componentDir = $hostDir . DS . $component;
            $componentCommandDir = $componentDir . DS . 'src';
            if (! is_dir($componentCommandDir)) {
                continue;
            }

            $ns = ComponentHelper::getComponentNamespace($component, $componentDir);
            $this->componentNamespaces[] = $ns;

            // console component
            if ($component === $this->consoleName) {
                $this->scanNamespaces[$ns] = $componentCommandDir;
                continue;
            }

            foreach ($this->serverScan as $dir) {
                $scanDir = $componentCommandDir . DS . $dir;
                if (!is_dir($scanDir)) {
                    continue;
                }

                $scanNs                        = $ns . "\\" . $dir;
                $this->scanNamespaces[$scanNs] = $scanDir;
            }
        }
    }
}