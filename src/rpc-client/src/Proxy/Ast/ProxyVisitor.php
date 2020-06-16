<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Client\Proxy\Ast;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use Swoft\Proxy\Ast\Visitor\Visitor;
use Swoft\Rpc\Client\Concern\ServiceTrait;
use Swoft\Stdlib\Helper\Str;
use function array_unshift;
use function sprintf;

/**
 * Class ProxyVisitor
 *
 * @since 2.0
 */
class ProxyVisitor extends Visitor
{
    /**
     * Namespace. eg: "App\Rpc\Lib"
     *
     * @var string
     */
    private $namespace = '';

    /**
     * New class name suffix. eg: "IGNORE_5e92a0ad04171"
     *
     * @var string
     */
    private $proxyId;

    /**
     * Origin interface class name. eg: "App\Rpc\Lib\UserInterface"
     *
     * @var string
     */
    private $originalInterfaceName = '';

    /**
     * Proxy class name without namespace. eg: "UserInterface_IGNORE_5e92a0ad04171"
     *
     * @var string
     */
    private $proxyName = '';

    /**
     * Aop class name
     *
     * @var string
     */
    private $serviceTrait;

    /**
     * ProxyVisitor constructor.
     *
     * TODO 提前传入原类名，可以直接得到 namespace, proxyName, originalInterfaceName
     *
     * @param string $proxyId
     * @param string $traitClassName
     */
    public function __construct(string $proxyId = '', string $traitClassName = ServiceTrait::class)
    {
        $this->serviceTrait = $traitClassName;
        $this->proxyId      = $proxyId ?: Str::getUniqid();
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

        // Origin interface node
        if ($node instanceof Node\Stmt\Interface_) {
            $name = $node->name->toString();

            $this->proxyName             = sprintf('%s_%s', $name, $this->proxyId);
            $this->originalInterfaceName = sprintf('%s\\%s', $this->namespace, $name);

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
        // Parse new interface node
        if ($node instanceof Node\Stmt\Interface_) {
            $newClassNodes = [
                'flags'      => 0,
                'stmts'      => $node->stmts,
                'implements' => [
                    new Node\Name('\\' . $this->originalInterfaceName)
                ],
            ];

            return new Node\Stmt\Class_($this->proxyName, $newClassNodes);
        }

        // Parse class method and rewrite public and protected
        if ($node instanceof ClassMethod) {
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

        $traitNode     = $this->getTraitNode();
        $orgMethodNode = $this->getOriginalClassNameMethodNode();

        array_unshift($classNode->stmts, $traitNode, $orgMethodNode);
        return $nodes;
    }

    /**
     * Proxy method
     *
     * @param ClassMethod $node
     *
     * @return ClassMethod
     */
    private function proxyMethod(ClassMethod $node): ClassMethod
    {
        $methodName = $node->name->toString();

        // TODO Origin method params
        $params = [];
        foreach ($node->params as $key => $param) {
            $params[] = $param;
        }

        // Proxy method params
        $newParams = [
            new Node\Scalar\String_($this->originalInterfaceName),
            new Node\Scalar\String_($methodName),
            new Node\Expr\FuncCall(new Node\Name('func_get_args')),
        ];

        // Proxy method call
        $proxyCall = new Node\Expr\MethodCall(new Node\Expr\Variable('this'), '__proxyCall', $newParams);

        // New method stmts
        $type = $node->returnType;
        $stmt = new Node\Stmt\Return_($proxyCall);
        if ($type && $type instanceof Node\Identifier && $type->name === 'void') {
            $stmt = new Node\Stmt\Expression($proxyCall);
        }

        // Return `self` to return `originalClassName`
        $returnType = $node->returnType;
        if ($returnType instanceof Node\Name && $returnType->toString() === 'self') {
            $returnType->parts = [
                sprintf('\\%s', $this->originalInterfaceName)
            ];
        }

        // New method nodes
        $newMethodNodes = [
            'flags'      => $node->flags,
            'byRef'      => $node->byRef,
            'name'       => $node->name,
            'params'     => $node->params,
            'returnType' => $returnType,
            'stmts'      => [
                $stmt
            ],
        ];

        return new ClassMethod($methodName, $newMethodNodes);
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
    public function getOriginalInterfaceName(): string
    {
        return $this->originalInterfaceName;
    }

    /**
     * @return string
     */
    public function getProxyName(): string
    {
        return $this->proxyName;
    }

    /**
     * Get aop trait node
     *
     * @return Node\Stmt\TraitUse
     */
    private function getTraitNode(): Node\Stmt\TraitUse
    {
        return new Node\Stmt\TraitUse([
            new Node\Name('\\' . $this->serviceTrait)
        ]);
    }

    /**
     * Get original class method node
     *
     * @return ClassMethod
     */
    private function getOriginalClassNameMethodNode(): ClassMethod
    {
        // Add getOriginalClassName method
        return new ClassMethod('getOriginalClassName', [
            'flags'      => Node\Stmt\Class_::MODIFIER_PUBLIC,
            'returnType' => 'string',
            'stmts'      => [
                new Node\Stmt\Return_(new Node\Scalar\String_($this->getOriginalInterfaceName()))
            ],
        ]);
    }
}
