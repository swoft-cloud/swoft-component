<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-01
 * Time: 20:03
 */

namespace Swoft\Event\Annotation\Parser;

use Doctrine\Common\Annotations\AnnotationException;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Event\Annotation\Mapping\Listener;

/**
 * Class ListenerParser
 * @since 2.0
 * @package Swoft\Event\Annotation\Parser
 *
 * @AnnotationParser(Listener::class)
 */
class ListenerParser extends Parser
{
    /**
     * @param int      $type
     * @param Listener $annotation
     *
     * @return array
     * @throws AnnotationException
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_CLASS) {
            throw new AnnotationException('`@Listener` must be defined by class!');
        }

        return [$this->className, $this->className, Bean::SINGLETON, ''];
    }
}