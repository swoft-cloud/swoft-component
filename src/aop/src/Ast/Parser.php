<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Ast;

use Swoole\Coroutine as SwCoroutine;

/**
 * Class Parser
 *
 * @author  huangzhhui <h@swoft.com>
 * @package Swoft\Aop\Ast
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
     * @var bool
     */
    protected $useAsyncIO;

    /**
     * Parser constructor.
     *
     * @param \Swoft\Aop\Ast\ClassLoader $classLoader
     * @param \PhpParser\Parser          $astParser
     * @param bool                       $useAsyncIO
     */
    public function __construct(ClassLoader $classLoader, \PhpParser\Parser $astParser, bool $useAsyncIO = false)
    {
        $this->setClassLoader($classLoader);
        $this->setAstParser($astParser);
        $this->setUseAsyncIO($useAsyncIO);
    }

    /**
     * @param string      $class
     * @param string|null $code
     * @return null|\PhpParser\Node\Stmt[]
     */
    public function getOrParse(string $class, string $code = null)
    {
        if (! AstCollector::has($class)) {
            $ast = $this->parse($class, $code);
            $ast && AstCollector::set($class, $ast);
        }
        return AstCollector::get($class);
    }

    /**
     * @param string      $class
     * @param string|null $code
     * @return null|\PhpParser\Node\Stmt[]
     */
    public function parse(string $class, string $code = null)
    {
        if (! $code) {
            $file = $this->getClassLoader()->getFileByClassName($class);
            $code = $this->getCodeByFile($file);
        }
        return $this->getAstParser()->parse($code);
    }

    /**
     * @param string $file
     * @return string
     */
    private function getCodeByFile(string $file): string
    {
        if (! \file_exists($file) || ! is_readable($file)) {
            return '';
        }
        // If read file co-method exist and running in Coroutine context,then use co-method to get file contents
        if ($this->isUseAsyncIO() && SwCoroutine::getuid() > 0 && method_exists(SwCoroutine::class, 'readFile')) {
            $code = SwCoroutine::readFile($file);
        } else {
            $code = \file_get_contents($file);
        }

        return (string)$code;
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
    public function setAstParser(\PhpParser\Parser $astParser): self
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
    public function setClassLoader(ClassLoader $classLoader): self
    {
        $this->classLoader = $classLoader;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseAsyncIO(): bool
    {
        return $this->useAsyncIO;
    }

    /**
     * @param bool $useAsyncIO
     * @return Parser
     */
    public function setUseAsyncIO($useAsyncIO): self
    {
        $this->useAsyncIO = $useAsyncIO;
        return $this;
    }
}
