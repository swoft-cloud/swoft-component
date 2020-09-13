<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Rpc\Protocol;

/**
 * Class Service
 *
 * @since 2.0
 *
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *     @Attribute("version", type="string"),
 * })
 */
class Service
{
    /**
     * @var string
     */
    private $version = Protocol::DEFAULT_VERSION;

    /**
     * Service constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->version = $values['value'];
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
}
