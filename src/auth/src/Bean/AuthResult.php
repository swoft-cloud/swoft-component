<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Bean;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Scope;

/**
 * @Bean(scope=Scope::PROTOTYPE)
 */
class AuthResult
{
    /**
     * @var string
     */
    protected $identity = '';

    /**
     * @var array
     */
    protected $extendedData = [];

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;
        return $this;
    }

    public function getExtendedData(): array
    {
        return $this->extendedData;
    }

    public function setExtendedData(array $extendedData): self
    {
        $this->extendedData = $extendedData;
        return $this;
    }
}
