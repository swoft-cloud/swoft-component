<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;

/**
 * Class TimerAfterContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class TimerAfterContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @var int
     */
    private $timerId = 0;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @param int   $timerId
     * @param array $params
     *
     * @return TimerAfterContext
     */
    public static function new(int $timerId, array $params): self
    {
        $self          = self::__instance();
        $self->timerId = $timerId;
        $self->params  = $params;

        return $self;
    }

    /**
     * @return int
     */
    public function getTimerId(): int
    {
        return $this->timerId;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
