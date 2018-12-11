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
