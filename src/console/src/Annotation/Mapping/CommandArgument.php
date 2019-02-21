<?php

namespace Swoft\Console\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Swoft\Console\Input\AbstractFlag;

/**
 * Class CommandArgument
 * @since 2.0
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 * @Attributes(
 *     @Attribute("name", type="string"),
 *     @Attribute("desc", type="string")
 * )
 */
final class CommandArgument extends AbstractFlag
{
}
