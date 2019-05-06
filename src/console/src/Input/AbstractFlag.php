<?php declare(strict_types=1);

namespace Swoft\Console\Input;

use function strtoupper;
use Swoft\Console\Annotation\Mapping\Command;
use function trim;
use function ucfirst;

/**
 * Class AbstractFlag
 * @since 2.0
 */
abstract class AbstractFlag
{
    /**
     * The option name
     *
     * @var string
     */
    private $name;

    /**
     * The option description message text
     *
     * @var string
     */
    private $desc = '';

    /**
     * The option mode value.
     *
     * @see Command::OPT_BOOLEAN, Command::OPT_REQUIRED and Command::OPT_*
     * @var int
     */
    private $mode = 0;

    /**
     * The option value data type. (eg: 'string', 'array', 'mixed', 'value')
     *
     * @var string
     */
    private $type = 'value';

    /**
     * The option/argument default value
     *
     * @var mixed
     */
    private $default;

    /**
     * Class constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = (string)$values['value'];
        } elseif (isset($values['name'])) {
            $this->name = (string)$values['name'];
        }

        // clear space and '-'
        $this->name = trim($this->name, '- ');

        if (!empty($values['desc'])) {
            $this->desc = trim((string)$values['desc']);
        }

        if (isset($values['mode'])) {
            $this->mode = (int)$values['mode'];
        }

        if (isset($values['type'])) {
            $this->type = (string)$values['type'];
        }

        if (isset($values['default'])) {
            $this->default = $values['default'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc ? ucfirst($this->desc) : '';
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * @param bool $upper
     * @return string
     */
    public function getType(bool $upper = true): string
    {
        if ($this->type) {
            return $upper ? strtoupper($this->type) : $this->type;
        }

        return '';
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }
}
