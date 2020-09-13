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
use Swoft\Aop\Annotation\Mapping\Before;
use Swoft\Aop\AspectRegister;
use Swoft\Aop\Exception\AopException;

/**
 * Class BeforeParser
 *
 * @AnnotationParser(Before::class)
 *
 * @since 2.0
 */
class BeforeParser extends Parser
{
    /**
     * Parse `Before` annotation
     *
     * @param int    $type
     * @param Before $annotationObject
     *
     * @return array
     * @throws AopException
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AopException('`@Before` must be defined by method!');
        }

        AspectRegister::registerAdvice(AspectRegister::ADVICE_BEFORE, $this->className, $this->methodName);

        return [];
    }
}
