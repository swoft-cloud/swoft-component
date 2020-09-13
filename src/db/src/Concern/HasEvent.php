<?php declare(strict_types=1);

namespace Swoft\Db\Concern;

use Swoft;
use Swoft\Db\Eloquent\Model;

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
     */
    protected function fireEvent(string $event, ...$args): bool
    {
        // Trigger method public event
        $eventPublicResult = Swoft::trigger($event, $this, ...$args);
        if ($eventPublicResult->isPropagationStopped() === true) {
            return false;
        }

        // Not model no trigger
        if ($this->getContextName() !== 'model') {
            return true;
        }

        // Trigger model method event
        $modelEventResult = Swoft::trigger($this->getModelEventName($event), $this, ...$args);

        return !($modelEventResult->isPropagationStopped() === true);
    }

    /**
     * @param string $event
     *
     * @return string
     */
    private function getModelEventName(string $event): string
    {
        $methods = explode('.', $event);
        $method  = end($methods);

        $name = str_replace('\\', '/', static::class);
        $name = basename($name);

        $modelName = lcfirst($name);

        $prefix = $this->getContextName();

        return "swoft.$prefix.$modelName.$method";
    }

    /**
     * @return string
     */
    private function getContextName(): string
    {
        if ($this instanceof Model) {
            return 'model';
        }

        return 'db';
    }
}
