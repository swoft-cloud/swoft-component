<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Aop\Ast\Visitors;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\NodeFinder;
use PhpParser\NodeVisitorAbstract;

/**
 * Class ProxyVisitor
 *
 * @author  huangzhhui <h@swoft.com>
 * @package Swoft\Aop\Ast\Visitors
 */
class ProxyVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    protected $className = '';

    /**
     * @var string
     */
    protected $proxyId = '';

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var array
     */
    protected $usedNamespaces = [];

    /**
     * @var string
     */
    protected $extends = '';

    /**
     * ProxyVisitor constructor.
     *
     * @param string $className
     * @param string $proxyId
     */
    public function __construct($className = '', $proxyId = '')
    {
        $this->setClassName($className);
        $this->setProxyId($proxyId);
    }

    /**
     * Called when entering a node.
     * Return value semantics:
     *  * null
     *        => $node stays as-is
     *  * NodeTraverser::DONT_TRAVERSE_CHILDREN
     *        => Children of $node are not traversed. $node stays as-is
     *  * NodeTraverser::STOP_TRAVERSAL
     *        => Traversal is aborted. $node stays as-is
     *  * otherwise
     *        => $node is set to the return value
     *
     * @param Node $node Node
     * @return null|int|Node Replacement node (or special return value)
     */
    public function enterNode(Node $node)
    {
        // Collect namespace for ProxyClass
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name->toString();
        } elseif ($node instanceof Class_ && $node->extends instanceof Name) {
            $this->extends = $node->extends->toString();
        } elseif ($node instanceof Use_ && $node->uses) {
            foreach ($node->uses as $useUse) {
                $this->usedNamespaces[$useUse->getAlias()->toString()] = $useUse->name->toString();
            }
        }
    }

    /**
     * Called when leaving a node.
     * Return value semantics:
     *  * null
     *        => $node stays as-is
     *  * NodeTraverser::REMOVE_NODE
     *        => $node is removed from the parent array
     *  * NodeTraverser::STOP_TRAVERSAL
     *        => Traversal is aborted. $node stays as-is
     *  * array (of Nodes)
     *        => The return value is merged into the parent array (at the position of the $node)
     *  * otherwise
     *        => $node is set to the return value
     *
     * @param Node $node Node
     * @return null|int|Node|Node[] Replacement node (or special return value)
     */
    public function leaveNode(Node $node)
    {
        // Clear all comment
        $node->getDocComment() && $node->setDocComment(new Doc(''));
        // Proxy Class
        if ($node instanceof Class_) {
            // Create proxy class base on parent class
            return new Class_($this->getProxyClassName(), [
                'flags'   => $node->flags,
                'stmts'   => $node->stmts,
                'extends' => new Name('\\' . $this->getClassName()),
            ]);
        }
        // Rewrite public and protected methods, without static methods
        if ($node instanceof ClassMethod && ! $node->isStatic() && ($node->isPublic() || $node->isProtected())) {
            $methodName = $node->name->toString();
            if ($methodName === 'getOriginalClassName') {
                return;
            }
            // Rebuild closure uses, only variable
            $uses = [];
            foreach ($node->params as $key => $param) {
                if ($param instanceof Param) {
                    $uses[$key] = new Param($param->var, null, null, true);
                }
            }
            $params = [
                // Add method to an closure
                new Closure([
                    'static' => $node->isStatic(),
                    'uses'   => $uses,
                    'stmts'  => $node->stmts,
                ]),
                new String_($methodName),
                new FuncCall(new Name('func_get_args')),
            ];
            $stmts = [
                new Return_(new MethodCall(new Variable('this'), '__proxyCall', $params))
            ];
            $returnType = $node->getReturnType();
            if ($returnType instanceof Name && $returnType->toString() === 'self') {
                $returnType = new Name('\\' . $this->getClassName());
            }
            return new ClassMethod($methodName, [
                'flags'      => $node->flags,
                'byRef'      => $node->byRef,
                'params'     => $node->params,
                'returnType' => $returnType,
                'stmts'      => $stmts,
            ]);
        }
        if ($this->extends && $node instanceof Node\Expr\StaticCall && $node->class instanceof Name && $node->class->toString() === 'parent') {
            $parentClass = $this->getParentClassFullName();
            if ($parentClass) {
                return new Node\Expr\StaticCall(new Name($parentClass), $node->name, $node->args, $node->getAttributes());
            }
        }
    }

    /**
     * Called once after traversal.
     * Return value semantics:
     *  * null:      $nodes stays as-is
     *  * otherwise: $nodes is set to the return value
     *
     * @param Node[] $nodes Array of nodes
     * @return null|Node[] Array of nodes
     */
    public function afterTraverse(array $nodes)
    {
        $addEnhancementMethods = $addGetOriginalClassNameMethod = $addInvokeTargetMethod = true;
        $nodeFinder = new NodeFinder();
        $nodeFinder->find($nodes, function (Node $node) use (
            &$addEnhancementMethods,
            &$addGetOriginalClassNameMethod,
            &$addInvokeTargetMethod
        ) {
            if ($node instanceof TraitUse) {
                foreach ($node->traits as $trait) {
                    // Did AopTrait trait use ?
                    if ($trait instanceof Name && $trait->toString() === '\Swoft\Aop\AopTrait') {
                        $addEnhancementMethods = false;
                        break;
                    }
                }
            } elseif ($node instanceof ClassMethod && $node->name->toString() === 'getOriginalClassName') {
                // Has getOriginalClassName method ?
                $addGetOriginalClassNameMethod = false;
            } elseif ($node instanceof ClassMethod && $node->name->toString() === '__invokeTarget') {
                // Has __invokeTarget method ?
                $addInvokeTargetMethod = false;
            }
        });
        // Find Class Node and then Add Aop Enhancement Methods nodes and getOriginalClassName() method
        $classNode = $nodeFinder->findFirstInstanceOf($nodes, Class_::class);
        $addEnhancementMethods && array_unshift($classNode->stmts, $this->getAopTraitUseNode());
        $addGetOriginalClassNameMethod && \is_array($classNode->stmts) && array_unshift($classNode->stmts, $this->getOrigianalClassNameMethodNode());
        $addInvokeTargetMethod && \is_array($classNode->stmts) && array_unshift($classNode->stmts, $this->getInvokeTargetMethodNode());
        return $nodes;
    }

    /**
     * @return string
     */
    public function getFullProxyClassName(): string
    {
        return $this->namespace . '\\' . $this->getProxyClassName();
    }

    /**
     * @return string
     */
    public function getProxyClassName(): string
    {
        return \basename(str_replace('\\', '/', $this->getClassName())) . '_' . $this->getProxyId();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return ProxyVisitor
     */
    public function setClassName($className): self
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return string
     */
    public function getProxyId(): string
    {
        return $this->proxyId;
    }

    /**
     * @param string $proxyId
     * @return ProxyVisitor
     */
    public function setProxyId($proxyId): self
    {
        $this->proxyId = $proxyId;
        return $this;
    }

    /**
     * @return \PhpParser\Node\Stmt\TraitUse
     */
    private function getAopTraitUseNode(): TraitUse
    {
        // Use AopTrait trait use node
        return new TraitUse([new Name('\Swoft\Aop\AopTrait')]);
    }

    /**
     * @return \PhpParser\Node\Stmt\ClassMethod
     */
    private function getOrigianalClassNameMethodNode(): ClassMethod
    {
        // Add getOriginalClassName() method node
        return new ClassMethod('getOriginalClassName', [
            'flags'      => Class_::MODIFIER_PUBLIC,
            'returnType' => 'string',
            'stmts'      => [
                new Return_(new String_($this->getClassName()))
            ],
        ]);
    }

    /**
     * @return \PhpParser\Node\Stmt\ClassMethod
     */
    private function getInvokeTargetMethodNode(): ClassMethod
    {
        // Add getOriginalClassName() method node
        return new ClassMethod('__invokeTarget', [
            'flags'      => Class_::MODIFIER_PUBLIC,
            'params'     => [
                new Param(new Variable('method'), null, 'string'),
                new Param(new Variable('args'), null, 'array'),
            ],
            'stmts'      => [
                new Return_(new Node\Expr\StaticCall(new Name('parent'), new Variable('method'), [
                    new Node\Arg(new Variable('args'), false, true)
                ]))
            ],
        ]);
    }

    /**
     * @return string
     */
    private function getParentClassFullName(): string
    {
        $name = null;
        if ($this->extends) {
            if ($this->usedNamespaces) {
                foreach ($this->usedNamespaces as $alias => $usedNamespace) {
                    if ($alias === $this->extends) {
                        $name = $usedNamespace;
                        break;
                    }
                }
            } else {
                $name = $this->namespace . '\\' . $this->extends;
            }
        }
        return $name !== null ? '\\' . $name : '';
    }
}
