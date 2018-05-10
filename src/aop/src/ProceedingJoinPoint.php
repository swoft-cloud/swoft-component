<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop;

use Swoft\App;

/**
 * ProceedingJoinPoint
 */
class ProceedingJoinPoint extends JoinPoint implements ProceedingJoinPointInterface
{
    /**
     * @var array
     */
    private $advice;

    /**
     * @var array
     */
    private $advices;

    /**
     * ProceedingJoinPoint constructor.
     *
     * @param object $target
     * @param string $method
     * @param array  $args
     * @param array  $advice
     * @param array  $advices
     */
    public function __construct($target, string $method, array $args, array $advice, array $advices)
    {
        parent::__construct($target, $method, $args, null);
        $this->advice  = $advice;
        $this->advices = $advices;
    }

    /**
     * proceed
     *
     * @param array $params
     * If the params is not empty, the params is used to call the method of target
     *
     * @return mixed
     */
    public function proceed($params = [])
    {
        // before
        if (isset($this->advice['before']) && !empty($this->advice['before'])) {
            list($aspectClass, $aspectMethod) = $this->advice['before'];
            $aspect = App::getBean($aspectClass);
            $aspect->$aspectMethod();
        }

        // execute
        if (empty($this->advices)) {
            $methodParams = !empty($params) ? $params : $this->args;
            $result       = $this->target->__invokeTarget($this->method, $methodParams);
        } else {
            $result = $this->target->__doAdvice($this->method, $this->args, $this->advices);
        }

        return $result;
    }

    public function reProceed(array $args = [])
    {
    }
}
