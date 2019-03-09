<?php

namespace Swoft\Console\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Console\AbstractHandler;

/**
 * Class CommandHandler
 * @since 2.0
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
final class CommandHandler extends AbstractHandler
{
    public const GROUP  = '';
    public const METHOD = 'execute';

    /**
     * Custom usage help information
     *
     * @var string
     */
    private $usage = '{fullCommand} [arguments ...] [options ...]';

    /**
     * @var string
     */
    private $method = self::METHOD;

    /**
     * Command constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        parent::__construct($values);

        if (isset($values['usage'])) {
            $this->usage = \trim($values['usage']);
        }

        if (isset($values['method'])) {
            $this->method = \trim($values['method']);
        }
    }

    /**
     * @return string
     */
    public function getUsage(): string
    {
        return $this->usage;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method ?: self::METHOD;
    }
}
