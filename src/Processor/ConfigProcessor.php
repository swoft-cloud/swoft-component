<?php

namespace Swoft\Processor;

use Swoft\Annotation\AnnotationRegister;

/**
 * Config processor
 */
class ConfigProcessor extends Processor
{
    /**
     * Handle config
     */
    public function handle(): bool
    {
        if (!$this->application->beforeBean()) {
            return false;
        }

        //        var_dump(AnnotationRegister::getAnnotations());
//        var_dump(AnnotationRegister::getParsers());
        echo 'config' . PHP_EOL;

        return $this->application->afterConfig();
    }
}