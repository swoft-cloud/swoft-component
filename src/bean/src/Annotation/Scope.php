<?php

namespace Swoft\Bean\Annotation;

/**
 * Bean scope
 */
final class Scope
{
    /**
     * Singleton
     */
    const SINGLETON = 1;

    /**
     * Always create an instance
     */
    const PROTOTYPE = 2;
}
