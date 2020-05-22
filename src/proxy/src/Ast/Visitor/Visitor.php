<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Proxy\Ast\Visitor;

use PhpParser\NodeVisitorAbstract;
use Swoft\Proxy\Contract\VisitorInterface;

/**
 * Class Visitor
 *
 * @package Swoft\Proxy\Ast\Visitor
 */
abstract class Visitor extends NodeVisitorAbstract implements VisitorInterface
{
}
