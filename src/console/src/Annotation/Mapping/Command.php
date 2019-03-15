<?php

namespace Swoft\Console\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Swoft\Console\AbstractHandler;

/**
 * The annotation of command controller
 *
 * @Annotation
 * @Target("CLASS")
 * @Attributes(
 *     @Attribute("name", type="string"),
 *     @Attribute("alias", type="string")
 * )
 */
final class Command extends AbstractHandler
{
    // fixed args and opts for a command/controller-command
    public const ARG_REQUIRED = 1;
    public const ARG_OPTIONAL = 2;
    public const ARG_IS_ARRAY = 4;

    public const OPT_BOOLEAN  = 1; // eq symfony InputOption::VALUE_NONE
    public const OPT_REQUIRED = 2;
    public const OPT_OPTIONAL = 4;
    public const OPT_IS_ARRAY = 8; // allow multi value

    /**
     * Default command in the group
     *
     * @var string
     */
    private $defaultCommand = '';

    /**
     * Command constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        parent::__construct($values);

        if (isset($values['defaultCommand'])) {
            $this->defaultCommand = \trim($values['defaultCommand']);
        }
    }

    /**
     * @return string
     */
    public function getDefaultCommand(): string
    {
        return $this->defaultCommand;
    }
}
