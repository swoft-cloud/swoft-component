<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Bean\Testing\Definition;

class InterfaceBeanDefinition
{
    /**
     * @var PrimaryInterfaceThree
     */
    private $pinterface;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->pinterface->getName();
    }
}
