<?php declare(strict_types=1);

namespace Swoft\Console;

use Swoft\Stdlib\Helper\Str;

/**
 * Class AbstractHandler - base class for Command and CommandHandler
 * @since 2.0
 */
abstract class AbstractHandler
{
    /**
     * Command group name
     *
     * @var string
     */
    private $name = '';

    /**
     * The group description message text
     *
     * @var string
     */
    private $desc = '';

    /**
     * Command group name alias. Allow add multi by ','
     *
     * @var string
     */
    private $alias = '';

    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @var bool
     */
    private $coroutine = true;


    /**
     * Command constructor.
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

        if (isset($values['alias'])) {
            $this->alias = \trim((string)$values['alias']);
        }

        if (!empty($values['desc'])) {
            $this->desc = \trim((string)$values['desc']);
        }

        if (isset($values['enabled'])) {
            $this->enabled = (bool)$values['enabled'];
        }

        if (isset($values['coroutine'])) {
            $this->coroutine = (bool)$values['coroutine'];
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
        return $this->desc;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return $this->alias ? Str::explode($this->alias) : [];
    }

    /**
     * @return bool
     */
    public function isCoroutine(): bool
    {
        return $this->coroutine;
    }
}
