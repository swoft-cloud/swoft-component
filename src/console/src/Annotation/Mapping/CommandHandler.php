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
    /**
     * Custom usage help information
     *
     * @var string
     */
    private $usage = '{fullCommand} [arguments ...] [options ...]';

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
    }

    /**
     * @return string
     */
    public function getUsage(): string
    {
        return $this->usage;
    }
}
