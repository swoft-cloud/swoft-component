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
    private $originalClassName = '';

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
            return null;
        }

        // Origin class node
        if ($node instanceof Node\Stmt\Class_) {
            $name = $node->name->toString();

            $this->proxyName         = sprintf('%s_%s', $name, $this->proxyId);
            $this->originalClassName = sprintf('%s\\%s', $this->namespace, $name);

            return null;
        }

        return null;
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
                'extends' => new Node\Name('\\' . $this->originalClassName),
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

            return $this->proxyMethod($node);
        }

        return $node;
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

        /** @var Node\Stmt\Class_ $classNode */
        $classNode = $nodeFinder->findFirstInstanceOf($nodes, Node\Stmt\Class_::class);

        $traitNode          = $this->getTraitNode();
        $originalMethodNode = $this->getOrigianalClassNameMethodNode();

        array_unshift($classNode->stmts, $traitNode, $originalMethodNode);
        return $nodes;
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
    public function getOriginalClassName(): string
    {
        return $this->originalClassName;
    }

    /**
     * Proxy method
     *
     * @param Node\Stmt\ClassMethod $node
     *
     * @return Node\Stmt\ClassMethod
     */
    private function proxyMethod(Node\Stmt\ClassMethod $node)
    {
        $methodName = $node->name->toString();

        // Origin method params
        $params = [];
        foreach ($node->params as $key => $param) {
            $params[] = $param;
        }

        // Proxy method params
        $newParams = [
            new Node\Scalar\String_($this->originalClassName),
            new Node\Scalar\String_($methodName),
            new Node\Expr\FuncCall(new Node\Name('func_get_args')),
        ];

        // Proxy method call
        $proxyCall = new Node\Expr\MethodCall(
            new Node\Expr\Variable('this'),
            '__proxyCall',
            $newParams
        );

        // New method stmts
        $type = $node->returnType;
        $stmt = new Node\Stmt\Return_($proxyCall);
        if ($type != null && $type == 'void') {
            $stmt = new Node\Stmt\Expression($proxyCall);
        }

        // New method nodes
        $newMethodNodes = [
            'flags'      => $node->flags,
            'byRef'      => $node->byRef,
            'name'       => $node->name,
            'params'     => $node->params,
            'returnType' => $node->returnType,
            'stmts'      => [
                $stmt
            ],
        ];

        return new Node\Stmt\ClassMethod($methodName, $newMethodNodes);
    }

    /**
     * Get aop trait node
     *
     * @return Node\Stmt\TraitUse
     */
    private function getTraitNode(): Node\Stmt\TraitUse
    {
        return new Node\Stmt\TraitUse([
            new Node\Name('\\' . $this->aopClassName)
        ]);
    }

    /**
     * Get original class method node
     *
     * @return \PhpParser\Node\Stmt\ClassMethod
     */
    private function getOrigianalClassNameMethodNode(): Node\Stmt\ClassMethod
    {
        // Add getOriginalClassName method
        return new Node\Stmt\ClassMethod('getOriginalClassName', [
            'flags'      => Node\Stmt\Class_::MODIFIER_PUBLIC,
            'returnType' => 'string',
            'stmts'      => [
                new Node\Stmt\Return_(new Node\Scalar\String_($this->getOriginalClassName()))
            ],
        ]);
    }
}