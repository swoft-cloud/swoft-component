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

/**
 * Processor interface
 *
 * @since 2.0
 */
interface ProcessorInterface
{
    /**
     * Handle processor
     *
     * Return `true` is to continue
     */
    public function handle(): bool;
}
