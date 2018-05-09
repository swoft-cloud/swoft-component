<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Client\Service\Vistors;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Swoft\Rpc\Client\Service;

/**
 * Class ServiceVistor
 *
 * @author  huangzhhui <h@swoft.com>
 * @package Swoft\Rpc\Client\Service\Vistors
 */
class ServiceVistor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    protected $className = '';

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     *  constructor.
     *
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @param \PhpParser\Node $node
     * @return void
     */
    public function enterNode(Node $node)
    {
        // Collect namespace for ProxyClass
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name->toString();
        }
    }

    /**
     * @param \PhpParser\Node $node
     * @return null|int|Node Replacement node
     */
    public function leaveNode(Node $node)
    {
        // Clear all comment
        $node->getDocComment() && $node->setDocComment(new Doc(''));

        if ($node instanceof Node\Stmt\Namespace_) {
            // Remove namespace
            return $node->stmts;
        } elseif ($node instanceof Node\Stmt\Use_) {
            return NodeTraverser::REMOVE_NODE;
        } elseif ($node instanceof Node\Stmt\Interface_) {
            // Create class node by interface node
            return new Node\Stmt\Class_($this->className, [
                'stmts'      => $node->stmts,
                'extends'    => new Node\Name('\\' . Service::class),
                'implements' => [
                    new Node\Name($this->namespace . '\\' . $node->name->toString())
                ],
            ]);
        } elseif ($node instanceof Node\Stmt\ClassMethod) {
            // Rewrite class method
            $node->stmts = [
                new Node\Stmt\Return_(new Node\Expr\MethodCall(new Node\Expr\Variable('this'), 'call', [
                    new Node\Scalar\String_($node->name->toString()),
                    new Node\Expr\FuncCall(new Node\Name('func_get_args')),
                ])),
            ];
        }
    }
}
