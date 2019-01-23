<?php declare(strict_types=1);


namespace Swoft\Aop\Point;

/**
 * Class JoinPoint
 *
 * @since 2.0
 */
class JoinPoint implements JoinPointInterface
{
    /**
     * @var array
     */
    protected $args = [];

    /**
     * @var object
     */
    protected $target;

    /**
     * @var mixed
     */
    protected $return;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var Throwable
     */
    protected $catch;

    /**
     * JoinPoint constructor.
     *
     * @param object    $target the object of origin
     * @param string    $method the method of origin
     * @param array     $args   the params of method
     * @param mixed     $return the return of executed method
     * @param Throwable $catch  the throwable caught of the origin
     */
    public function __construct($target, string $method, array $args, $return = null, $catch = null)
    {
        $this->args   = $args;
        $this->return = $return;
        $this->target = $target;
        $this->method = $method;
        $this->catch  = $catch;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return object
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return null|Throwable
     */
    public function getCatch()
    {
        return $this->catch;
    }
}