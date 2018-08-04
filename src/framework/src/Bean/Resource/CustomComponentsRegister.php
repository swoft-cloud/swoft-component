<?php
namespace Swoft\Bean\Resource;

use Swoft\Helper\ComposerHelper;

trait CustomComponentsRegister
{
    /**
     * 注册用户自定义的组件
     * @author limx
     */
    public function registerCustomComponentsNamespace()
    {
        foreach ($this->customComponents as $ns) {
            $this->componentNamespaces[] = $ns;

            $componentCommandDir = ComposerHelper::getDirByNamespace($ns);
            foreach ($this->serverScan as $dir) {
                $scanDir = $componentCommandDir . DS . $dir;
                if (!is_dir($scanDir)) {
                    continue;
                }

                $scanNs = $ns . "\\" . $dir;
                $this->scanNamespaces[$scanNs] = $scanDir;
            }
        }
    }
}