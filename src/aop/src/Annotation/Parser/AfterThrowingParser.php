<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Aop\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Aop\Annotation\Mapping\AfterThrowing;
use Swoft\Aop\AspectRegister;

/**
 * Class AfterThrowingParser
 *
 * @AnnotationParser(AfterThrowing::class)
 *
 * @since 2.0
 */
class AfterThrowingParser extends Parser
{
    /**
     * Parse `AfterThrowing` annotation
     *
     * @param int           $type
     * @param AfterThrowing $annotationObject
     *
     * @return array
     * @throws AnnotationException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('`@AfterThrowing` must be defined by method!');
        }

        AspectRegister::registerAdvice(AspectRegister::ADVICE_AFTERTHROWING, $this->className, $this->methodName);

        return [];
    }
}
