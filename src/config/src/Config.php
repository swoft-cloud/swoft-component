<?php declare(strict_types=1);

namespace Swoft\Config;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Parser\ParserInterface;
use Swoft\Config\Parser\PhpParser;
use Swoft\Stdlib\Collection;
use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Class Config
 *
 * @Bean("config")
 * @since 2.0
 */
class Config extends Collection
{
    /**
     * Php formatter
     */
    const TYPE_PHP = 'php';

    /**
     * Yaml formatter
     */
    const TYPE_YAML = 'yaml';

    /**
     * Base file name
     *
     * @var string
     */
    private $base = 'base';

    /**
     * Default config type
     *
     * @var string
     */
    private $type = self::TYPE_PHP;

    /**
     * Config path
     *
     * @var string
     */
    private $path = '';

    /**
     * Resource parsers
     *
     * @var array
     */
    private $parsers = [];

    /**
     * Init
     */
    public function init()
    {
        $parsers = $this->getConfigParser();
        if (!isset($parsers[$this->type])) {
            throw new \InvalidArgumentException('Resourcer is not exist! resourceType=' . $this->type);
        }

        /* @var ParserInterface $parser */
        $parser    = $parsers[$this->type];
        $parseData = $parser->parse($this);

        $this->items = $parseData;
    }

    /**
     * Get value by key
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return ArrayHelper::get($this->items, $key, $default);
    }

    /**
     * @return string
     */
    public function getBase(): string
    {
        return $this->base;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return ArrayHelper::has($this->items, $key);
    }

    /**
     * Get parse resourcers
     *
     * @return array
     */
    private function getConfigParser(): array
    {
        $parsers = $this->parsers;

        // Set default parser
        if (empty($this->parsers)) {
            $parsers = [
                self::TYPE_PHP => new PhpParser()
            ];
        }

        return $parsers;
    }
}