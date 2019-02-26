<?php

namespace Swoft\Processor;

/**
 * Config processor
 * @since 2.0
 */
class ConfigProcessor extends Processor
{
    /**
     * Handle config
     */
    public function handle(): bool
    {
        if (!$this->application->beforeConsole()) {
            return false;
        }

        //        var_dump(AnnotationRegister::getAnnotations());
//        var_dump(AnnotationRegister::getParsers());
        echo 'config' . PHP_EOL;

        return $this->application->afterConfig();
    }
}