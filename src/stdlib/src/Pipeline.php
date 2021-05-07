<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Stdlib;

use SplObjectStorage;
use function is_callable;

/**
 * Class Pipeline
 *
 * @since 2.0
 * @see   https://github.com/ztsu/pipe
 */
class Pipeline
{
    /** @var SplObjectStorage */
    private $stages;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->stages = new SplObjectStorage;
    }

    /**
     * @param mixed ...$handlers
     *
     * @return $this
     */
    public function use(...$handlers): self
    {
        foreach ($handlers as $handler) {
            $this->stages->attach($handler);
        }

        return $this;
    }

    /**
     * Adds stage to the pipeline
     *
     * @param callable $stage fun
     *
     * @return Pipeline
     */
    public function add(callable $stage): self
    {
        if ($stage instanceof $this) {
            $stage->add(function ($payload) {
                return $this->invokeStage($payload);
            });
        }

        $this->stages->attach($stage);
        return $this;
    }

    /**
     * Runs pipeline with initial value
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function run($payload)
    {
        $this->stages->rewind();

        return $this->invokeStage($payload);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke($payload)
    {
        return $this->run($payload);
    }

    /**
     * @param mixed $payload
     *
     * @return mixed
     */
    protected function invokeStage($payload)
    {
        $stage = $this->stages->current();
        $this->stages->next();

        if (is_callable($stage)) {
            return $stage($payload, function ($payload) {
                return $this->invokeStage($payload);
            });
        }

        return $payload;
    }
}
