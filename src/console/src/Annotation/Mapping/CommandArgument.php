<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Console\Input\AbstractFlag;

/**
 * Class CommandArgument
 * @since 2.0
 * @Annotation
 * @Target("METHOD")
 * @Attributes(
 *     @Attribute("name", type="string"),
 *     @Attribute("desc", type="string")
 * )
 */
final class CommandArgument extends AbstractFlag
{
}
