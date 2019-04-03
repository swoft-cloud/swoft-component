<?php declare(strict_types=1);

namespace Swoft\Event\Annotation\Parser;

use Doctrine\Common\Annotations\AnnotationException;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\Manager\EventManager;

/**
 * Class ListenerParser
 * @since   2.0
 * @package Swoft\Event\Annotation\Parser
 *
 * @AnnotationParser(Subscriber::class)
 */
class SubscriberParser extends Parser
{
    /**
     * @var array
     */
    private static $subscribers = [];

    /**
     * @param int        $type
     * @param Subscriber $annotation
     *
     * @return array
     * @throws AnnotationException
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_CLASS) {
            throw new AnnotationException('`@Subscriber` must be defined on class!');
        }

        self::$subscribers[] = $this->className;

        return [$this->className, $this->className, Bean::SINGLETON, ''];
    }

    /**
     * Register collected event subscribers to EventManager
     *
     * @param EventManager $em
     * @return int
     */
    public static function addSubscribers(EventManager $em): int
    {
        foreach (self::$subscribers as $className) {
            $em->addSubscriber(new $className);
        }

        $count = \count(self::$subscribers);
        // Clear data
        self::$subscribers = [];

        return $count;
    }
}
