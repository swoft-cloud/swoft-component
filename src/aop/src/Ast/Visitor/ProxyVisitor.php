<?php declare(strict_types=1);


namespace Swoft\Aop\Ast\Visitor;

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Swoft\Aop\AopTrait;

/**
 * Class ProxyVisitor
 *
 * @since 2.0
 */
class ProxyVisitor extends NodeVisitorAbstract
{
    /**
     * Namespace
     *
     * @var string
     */
    private $namespace = '';

    /**
     * New class name
     *
     * @var string
     */
    private $proxyId = '';

    /**
     * Origin class name
     *
     * @var string
     */
    private $originClassName = '';

    /**
     * Proxy class name without namespace
     *
     * @var string
     */
    private $proxyName = '';

    /**
     * Aop class name
     *
     * @var string
     */
    private $aopClassName = '';

    /**
     * ProxyVisitor constructor.
     *
     * @param string $proxyId
     * @param string $aopClassName
     */
    public function __construct(string $proxyId = '', string $aopClassName = AopTrait::class)
    {
        $this->aopClassName = $aopClassName;
        $this->proxyId      = empty($proxyId) ? uniqid() : $proxyId;
    }

    /**
     * Enter node
     *
     * @param Node $node
     *
     * @return int|Node|null]
     */
    public function enterNode(Node $node)
    {
        // Namespace for proxy class name
        if ($node instanceof Node\Stmt\Namespace_) {

            $this->namespace = $node->name->toString();
            return;
        }

        // Origin class node
        if ($node instanceof Node\Stmt\Class_) {
            $name = $node->name->toString();

            $this->proxyName       = sprintf('%s_%s', $name, $this->proxyId);
            $this->originClassName = sprintf('%s\\%s', $this->namespace, $name);

            return;
        }
    }

    /**
     * Leave node
     *
     * @param Node $node
     *
     * @return int|Node|Node[]|null
     */
    public function leaveNode(Node $node)
    {
        // Parse new class node
        if ($node instanceof Node\Stmt\Class_) {
            $newClassNodes = [
                'flags'   => $node->flags,
                'stmts'   => $node->stmts,
                'extends' => new Node\Name('\\' . $this->originClassName),
            ];

            return new Node\Stmt\Class_($this->proxyName, $newClassNodes);
        }

        // Remove property node
        if ($node instanceof Node\Stmt\Property) {
            return NodeTraverser::REMOVE_NODE;
        }

        // Parse class method and rewrite public and protected
        if ($node instanceof Node\Stmt\ClassMethod) {
            if ($node->isPrivate() || $node->isStatic()) {
                return NodeTraverser::REMOVE_NODE;
            }

            $methodName = $node->name->toString();

            // Origin method params
            $params = [];
            foreach ($node->params as $key => $param) {
                $params[] = $param;
            }

            // Proxy method params
            $newParams = [
                new Node\Scalar\String_($this->originClassName),
                new Node\Scalar\String_($methodName),
                new Node\Expr\FuncCall(new Node\Name('func_get_args')),
            ];

            $proxyMerthodCall = new Node\Expr\MethodCall(
                new Node\Expr\Variable('this'),
                '__proxyCall',
                $newParams
            );

            $returnType = ($node->returnType != null) ? $node->returnType->toString() : '';

            // New method stmts
            $stmts = [
                new Node\Stmt\Return_($proxyMerthodCall)
            ];

            if ($returnType == 'void') {
                $stmts = [
                    new Node\Stmt\Expression($proxyMerthodCall)
                ];
            }

            // New method nodes
            $newMethodNodes = [
                'flags'      => $node->flags,
                'byRef'      => $node->byRef,
                'name'       => $node->name,
                'params'     => $node->params,
                'returnType' => $node->returnType,
                'stmts'      => $stmts,
            ];

            return new Node\Stmt\ClassMethod($methodName, $newMethodNodes);
        }


        return $node;
    }

    /**
     * Get proxy class name
     *
     * @return string
     */
    public function getProxyClassName(): string
    {
        return sprintf('%s\\%s', $this->namespace, $this->proxyName);
    }

    /**
     * Get proxy class name
     *
     * @return string
     */
    public function getOriginClassName(): string
    {
        return $this->getOriginClassName();
    }

    /**
     * After traverse
     *
     * @param array $nodes
     *
     * @return array|Node[]|null
     */
    public function afterTraverse(array $nodes)
    {
        $nodeFinder = new NodeFinder();
        $traitNode  = new Node\Stmt\TraitUse([new Node\Name('\\' . $this->aopClassName)]);

        // Add aop trait
        $classNode = $nodeFinder->findFirstInstanceOf($nodes, Node\Stmt\Class_::class);
        array_unshift($classNode->stmts, $traitNode);
        return $nodes;
    }
}