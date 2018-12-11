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
namespace Swoft\Rpc\Client\Bean\Annotation;

/**
 * The annotation of reference
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Reference
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $version = '0';

    /**
     * @var string
     */
    private $pool = '';

    /**
     * @var string
     */
    private $breaker = '';

    /**
     * @var string
     */
    private $packer = '';

    /**
     * @var string
     */
    private $fallback = '';

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['version'])) {
            $this->version = $values['version'];
        }
        if (isset($values['pool'])) {
            $this->pool = $values['pool'];
        }
        if (isset($values['breaker'])) {
            $this->breaker = $values['breaker'];
        }
        if (isset($values['packer'])) {
            $this->packer = $values['packer'];
        }
        if (isset($values['fallback'])) {
            $this->fallback = $values['fallback'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getPool(): string
    {
        return $this->pool;
    }

    /**
     * @return string
     */
    public function getBreaker(): string
    {
        return $this->breaker;
    }

    /**
     * @return string
     */
    public function getPacker(): string
    {
        return $this->packer;
    }

    /**
     * @return string
     */
    public function getFallback(): string
    {
        return $this->fallback;
    }
}
