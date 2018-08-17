<?php
namespace SwoftTest\Testing\Bean;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;

/**
 * Class Config
 * @Bean
 * @package SwoftTest\Testing\Bean
 */
class Config
{
    /**
     * @Value(env="${TEST_NAME}")
     * @var string
     */
    public $name = 'fail';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}