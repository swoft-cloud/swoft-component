<?php declare(strict_types=1);

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
