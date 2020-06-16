<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Client\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Rpc\Protocol;

/**
 * Class Reference
 *
 * @since 2.0
 *
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *     @Attribute("event", type="string"),
 * })
 */
class Reference
{
    /**
     * @var string
     *
     * @Required()
     */
    private $pool;

    /**
     * @var string
     */
    private $version = Protocol::DEFAULT_VERSION;

    /**
     * Reference constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->pool = $values['value'];
        } elseif (isset($values['pool'])) {
            $this->pool = $values['pool'];
        }

        if (isset($values['version'])) {
            $this->version = $values['version'];
        }
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
}
