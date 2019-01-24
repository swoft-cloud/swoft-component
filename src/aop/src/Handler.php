<?php declare(strict_types=1);


namespace Swoft\Aop;

use Swoft\Aop\Point\JoinPoint;
use Swoft\Aop\Point\ProceedingJoinPoint;
use Swoft\Bean\BeanFactory;

/**
 * Class Handler
 *
 * @since 2.0
 */
class Handler
{
    /**
     * @var AopTrait
     */
    private $target;

    /**
     * @var string
     */
    private $methodName = '';

    /**
     * @var array
     */
    private $args = [];

    /**
     * @var array
     */
    private $aspects = [];

    private $aspect;

    /**
     * Handler constructor.
     *
     * @param AopTrait $target
     * @param string   $methodName
     * @param array    $args
     * @param array    $aspects
     */
    public function __construct($target, string $methodName, array $args, array $aspects)
    {
        $this->target     = $target;
        $this->methodName = $methodName;
        $this->args       = $args;
        $this->aspect     = array_shift($aspects);
        $this->aspects    = $aspects;
    }

    /**
     * @return mixed
     */
    public function doAspect()
    {

        $around = $this->aspect['around'] ?? [];
//        $afterThrowing  = $this->aspect['afterThrowing'] ?? [];
        $afterReturning = $this->aspect['afterReturning'] ?? [];
        $after          = $this->aspect['after'] ?? [];

        $result = null;
        try {
            if (!empty($around)) {
                $result = $this->doAdvice($around);
            } else {
                $result = $this->doTarget();
            }
        } catch (\Exception $e) {
            // After throwing
        }

        if (!empty($after)) {
            $this->doAdvice($after);
        }

        if (!empty($afterReturning)) {
            $this->doAdvice($afterReturning);
        }

        return $result;
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function doTarget(array $params = [])
    {
        $before = $this->aspect['before'] ?? [];

        $result = null;
        if (!empty($before)) {
            $this->doAdvice($before);
        }
        if (empty($this->aspects)) {
            $args   = empty($params) ? $this->args : $params;
            $result = $this->target->__invokeTarget($this->methodName, $args);
        } else {
            $result = $this->next()->doAspect();
        }

        return $result;
    }

    private function doAdvice($aspectAry)
    {
        list($aspectClass, $aspectMethod) = $aspectAry;

        $reflectionAry = BeanFactory::getReflectionClass($aspectClass);

        $params = $reflectionAry['methods'][$aspectMethod]['params'] ?? [];

        $aspectArgs = [];
        foreach ($params as $param) {
            /* @var \ReflectionType $reflectType */
            list(, $reflectType) = $param;
            if ($reflectType === null) {
                $aspectArgs[] = null;
                continue;
            }

            // JoinPoint object
            $type = $reflectType->getName();
            if ($type === JoinPoint::class) {
                $aspectArgs[] = new JoinPoint($this->target, $this->methodName, $this->args);
                continue;
            }

            // ProceedingJoinPoint object
            if ($type === ProceedingJoinPoint::class) {
                $proceedingJoinPoint = new ProceedingJoinPoint($this->target, $this->methodName, $this->args);
                $proceedingJoinPoint->setHandler($this);

                $aspectArgs[] = $proceedingJoinPoint;
                continue;
            }

            $aspectArgs[] = null;
        }

        $aspect = bean($aspectClass);

        return $aspect->$aspectMethod(...$aspectArgs);
    }

    /**
     * Next aspect
     */
    private function next(): Handler
    {
        $aspect = clone  $this;

        $aspect->aspect  = array_shift($this->aspects);
        $aspect->aspects = $this->aspects;

        return $aspect;
    }
}