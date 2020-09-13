<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Bean\Testing;

/**
 * Class BeanProperty
 *
 * @since 2.0
 */
abstract class BeanProperty
{
    /**
     * @var string
     */
    private $privateProp = '';

    /**
     * @var int
     */
    public $publicProp = 0;

    /**
     * @return string
     */
    public function getPrivateProp(): string
    {
        return $this->privateProp;
    }

    /**
     * @return int
     */
    public function getPublicProp(): int
    {
        return $this->publicProp;
    }
}
