<?php declare(strict_types=1);


namespace Swoft\Db\Concern;

use Swoft;
use Swoft\Db\Eloquent\Model;
use Swoft\Bean\Exception\ContainerException;

/**
 * Class HasEvent
 *
 * @since 2.0
 */
trait HasEvent
{
    /**
     * Fire the given event for the model.
     *
     * @param string $event
     * @param mixed  ...$args
     *
     * @return bool
     * @throws ContainerException
     */
    protected function fireEvent(string $event, ...$args): bool
    {
        // Trigger method public event
        $eventPublicResult = Swoft::trigger($this->getPublicEventName($event), null, $this);
        if ($eventPublicResult->isPropagationStopped() === true) {
            return false;
        }

        // Not model no trigger
        if ($this->getContextName() !== 'model') {
            return true;
        }
        // Trigger model method event
        $modelEventResult = Swoft::trigger($this->getEventName($event), null, $this, ...$args);
        if ($modelEventResult->isPropagationStopped() === true) {
            return false;
        }

        return true;
    }

    /**
     * @param string $method
     *
     * @return string
     */
    private function getEventName(string $method): string
    {
        $name = str_replace('\\', '/', static::class);
        $name = basename($name);

        $modelName = lcfirst($name);

        $prefix = $this->getContextName();

        return "swoft.$prefix.$modelName.$method";
    }

    /**
     * @param string $method
     *
     * @return string
     */
    protected function getPublicEventName(string $method)
    {
        $prefix = $this->getContextName();

        return "swoft.$prefix.$method";
    }

    /**
     * @return string
     */
    private function getContextName(): string
    {
        if (static::class instanceof Model) {
            return 'model';
        }
        return 'db';
    }
}
