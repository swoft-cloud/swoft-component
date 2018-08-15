<?php
namespace Swoft\Bean\Resource;

use Swoft\Helper\ComposerHelper;

trait CustomComponentsRegister
{
    /**
     * Register the server namespace
     * @author limx
     */
    public function registerServerNamespace()
    {
        foreach ($this->customComponents as $ns => $componentDir) {
            if (is_int($ns)) {
                $ns = $componentDir;
                $componentDir = ComposerHelper::getDirByNamespace($ns);
                $ns = rtrim($ns, "\\");
                $componentDir = rtrim($componentDir, "/");
            }

            $this->componentNamespaces[] = $ns;
            $componentDir = alias($componentDir);

            foreach ($this->serverScan as $dir) {
                $scanDir = $componentDir . DS . $dir;
                if (!is_dir($scanDir)) {
                    continue;
                }

                $scanNs = $ns . "\\" . $dir;
                $this->scanNamespaces[$scanNs] = $scanDir;
            }
        }
    }

    /**
     * Register the worker namespace
     * @author limx
     */
    public function registerWorkerNamespace()
    {
        foreach ($this->customComponents as $ns => $componentDir) {
            if (is_int($ns)) {
                $ns = $componentDir;
                $componentDir = ComposerHelper::getDirByNamespace($ns);
                $ns = rtrim($ns, "\\");
                $componentDir = rtrim($componentDir, "/");
            }

            $this->componentNamespaces[] = $ns;
            $componentDir = alias($componentDir);

            if (!is_dir($componentDir)) {
                continue;
            }

            $scanDirs = scandir($componentDir, null);

            foreach ($scanDirs as $dir) {
                if ($dir == '.' || $dir == '..') {
                    continue;
                }
                if (\in_array($dir, $this->serverScan, true)) {
                    continue;
                }

                $scanDir = $componentDir . DS . $dir;

                if (!is_dir($scanDir)) {
                    $this->scanFiles[$ns][] = $scanDir;
                    continue;
                }
                $scanNs = $ns . '\\' . $dir;

                $this->scanNamespaces[$scanNs] = $scanDir;
            }
        }
    }
}