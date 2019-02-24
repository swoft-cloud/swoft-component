<?php

namespace Swoft\Console\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Console\Input\AbstractFlag;
use Swoft\Stdlib\Helper\Str;

/**
 * Class CommandOption
 * @since 2.0
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 * @Attributes(
 *     @Attribute("name", type="string"),
 *     @Attribute("desc", type="string")
 * )
 */
final class CommandOption extends AbstractFlag
{
    /**
     * The option short name. (Allow add multi by ',')
     * Notice: each shortcut only allow one char
     *
     * @var string
     */
    private $short = '';

    /**
     * @return string
     */
    public function getShort(): string
    {
        return $this->short;
    }

    /**
     * get shorts
     * @return string[]
     */
    public function getShorts(): array
    {
        return Str::explode($this->short);
    }
}
