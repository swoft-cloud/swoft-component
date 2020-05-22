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

use Swoft\Contract\SwoftInterface;
use Swoft\SwoftApplication;

/**
 * Abstract processor
 *
 * @since 2.0
 */
abstract class Processor implements ProcessorInterface
{
    /**
     * Swoft application
     *
     * @var SwoftInterface|SwoftApplication
     */
    protected $application;

    /**
     * Processor constructor.
     *
     * @param SwoftInterface $application
     */
    public function __construct(SwoftInterface $application)
    {
        $this->application = $application;
    }
}
