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

namespace Swoft\Task\Bean\Annotation;

/**
 * Scheduled annotation
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class Scheduled
{
    /**
     * @var string
     */
    private $cron;

    /**
     * @var string
     */
    private $description = '';

    /**
     * Bean constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->cron = $values['value'];
        }

        if (isset($values['cron'])) {
            $this->cron = $values['cron'];
        }

        if (isset($values['description'])) {
            $this->description = $values['description'];
        }
    }

    /**
     * @return string
     */
    public function getCron(): string
    {
        return $this->cron;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
