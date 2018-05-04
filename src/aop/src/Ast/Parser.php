<?php

namespace Swoft\Aop\Ast;

use PhpParser\ParserFactory;
use Swoft\Bean\Annotation\Bean;


/**
 * Class Parser
 */
class Parser
{

    /**
     * @var ClassLoader
     */
    protected $classLoader;

    /**
     * @var \PhpParser\Parser
     */
    protected $astParser;

    /**
     * Parser constructor.
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->setClassLoader(new ClassLoader())->setAstParser((new ParserFactory())->create(ParserFactory::ONLY_PHP7));
    }

    /**
     * @param string $class
     * @return null|\PhpParser\Node\Stmt[]
     */
    public function getOrParse(string $class)
    {
        if (! AstCollector::has($class)) {
            $ast = $this->parse($class);
            $ast && AstCollector::set($class, $ast);
        }
        return AstCollector::get($class);
    }

    /**
     * @param string $class
     * @return null|\PhpParser\Node\Stmt[]
     */
    public function parse(string $class)
    {
        $file = $this->getClassLoader()->getFileByClassName($class);
        if (! file_exists($file)) {
            return null;
        }
        $code = file_get_contents($file);
        return $this->getAstParser()->parse($code);
    }

    /**
     * @return \PhpParser\Parser
     */
    public function getAstParser(): \PhpParser\Parser
    {
        return $this->astParser;
    }

    /**
     * @param \PhpParser\Parser $astParser
     * @return Parser
     */
    public function setAstParser(\PhpParser\Parser $astParser): Parser
    {
        $this->astParser = $astParser;
        return $this;
    }

    /**
     * @return ClassLoader
     */
    public function getClassLoader(): ClassLoader
    {
        return $this->classLoader;
    }

    /**
     * @param ClassLoader $classLoader
     * @return Parser
     */
    public function setClassLoader(ClassLoader $classLoader): Parser
    {
        $this->classLoader = $classLoader;
        return $this;
    }

}