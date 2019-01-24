<?php 


namespace Swoft\Aop\Ast;

use PhpParser\ErrorHandler;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use Swoft\Aop\Exception\AopException;
use Swoft\Stdlib\Helper\ComposerHelper;

/**
 * Class Parser
 *
 * @since 2.0
 */
class Parser
{
    /**
     * @var \PhpParser\Parser
     */
    private $parser;

    /**
     * Node vistors
     *
     * @var NodeVisitor[]
     *
     * @example
     * [
     *     'name' => new NodeVisitor(),
     *     'name' => new NodeVisitor(),
     *     'name' => new NodeVisitor(),
     * ]
     */
    private $nodeVistors;

    /**
     * Traverser
     *
     * @var NodeTraverser
     */
    private $traverser;

    /**
     * Pretty printer
     *
     * @var Standard
     */
    private $printer;

    /**
     * Parser constructor.
     *
     * @param int        $kind
     * @param Lexer|null $lexer
     * @param array      $parserOptions
     */
    public function __construct(int $kind = ParserFactory::ONLY_PHP7, Lexer $lexer = null, array $parserOptions = [])
    {
        $this->printer   = new Standard();
        $this->traverser = new NodeTraverser();
        $this->parser    = (new ParserFactory())->create($kind, $lexer, $parserOptions);
    }

    /**
     * Parse class by ast
     *
     * @param string            $className
     * @param ErrorHandler|null $errorHandler
     *
     * @return string
     * @throws AopException
     */
    public function parse(string $className, ErrorHandler $errorHandler = null): string
    {
        $code = $this->getCodeByClassName($className);
        $ast  = $this->parser->parse($code, $errorHandler);

        // Add vistors
        foreach ($this->nodeVistors as $name => $nodeVistor) {
            if ($nodeVistor instanceof NodeVisitor) {
                $this->traverser->addVisitor($nodeVistor);
            }
        }

        // New code by traverse
        $nodes   = $this->traverser->traverse($ast);
        $newCode = $this->printer->prettyPrint($nodes);

        return $newCode;
    }

    /**
     * Add node vistor
     *
     * @param string      $name
     * @param NodeVisitor $nodeVisitor
     */
    public function addNodeVisitor(string $name, NodeVisitor $nodeVisitor): void
    {
        $this->nodeVistors[$name] = $nodeVisitor;
    }

    /**
     * Get php code by class name
     *
     * @param string $className
     *
     * @return string
     * @throws AopException|\Exception
     */
    private function getCodeByClassName(string $className): string
    {
        // Get file by class name
        $file = ComposerHelper::getClassLoader()->findFile($className);

        if (!file_exists($file)) {
            throw new AopException(sprintf('%s file is not exist!', $file));
        }

        $phpCode = file_get_contents($file);

        return $phpCode;
    }
}