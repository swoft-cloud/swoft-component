<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/8/7
 * Time: 16:12
 */

namespace Swoft\Encrypt\Aspect;

use Swoft\Aop\ProceedingJoinPoint;
use Swoft\App;
use Swoft\Bean\Annotation\Around;
use Swoft\Bean\Annotation\Aspect;
use Swoft\Bean\Annotation\PointAnnotation;
use Swoft\Core\RequestContext;
use Swoft\Encrypt\Bean\Annotation\Encrypt;
use Swoft\Encrypt\Bean\Collector\EncryptCollector;
use Swoft\Encrypt\Handler\EncryptHandler;

/**
 * @Aspect()
 * @PointAnnotation(
 *      include={
 *          Encrypt::class
 *      }
 *  )
 * Class EncryptAspect
 * @package Swoft\Encrypt\Aspect
 */
class EncryptAspect
{
    /**
     * @Around()
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        /* @var Encrypt $classAnnotation*/
        /* @var Encrypt $annotation*/
        list($classAnnotation, $annotation) = $this->getAnnotation($proceedingJoinPoint);

        /* @var EncryptHandler $encryptHandler*/
        $encryptHandler = App::getBean(EncryptHandler::class); // 因底层bug, 应注入EncryptHandlerInterface

        $before = $annotation->getBefore() ?? $classAnnotation->getBefore() ?? App::getProperties()->get('encrypt')['before'];
        if ($before && method_exists($encryptHandler, $before)){
            $parsedBody = $encryptHandler->$before(request()->raw());
            if ($parsedBody){
                RequestContext::setRequest(request()->withParsedBody($parsedBody));
            }
        }

        $result = $proceedingJoinPoint->proceed(); // 后期兼容下参数注入

        $after = $annotation->getAfter() ?? $classAnnotation->getAfter() ?? App::getProperties()->get('encrypt')['after'];
        if ($after && method_exists($encryptHandler, $after)){
            $result = $encryptHandler->$after($result);
        }

        return $result;
    }

    private function getAnnotation(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $collector = EncryptCollector::getCollector();
        return [
            $collector[get_class($proceedingJoinPoint->getTarget())]['classAnnotation'],
            $collector[get_class($proceedingJoinPoint->getTarget())][$proceedingJoinPoint->getMethod()],
        ];
    }
}