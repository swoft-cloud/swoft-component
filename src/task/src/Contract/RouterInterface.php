<?php declare(strict_types=1);


namespace Swoft\Task\Contract;

/**
 * Class RouterInterface
 *
 * @since 2.0
 */
interface RouterInterface extends \Swoft\Contract\RouterInterface
{
    /**
     * @param string $className
     * @param string $taskName
     * @param string $mappingName
     */
    public function addRoute(string $className, string $taskName, string $mappingName): void;

    /**
     * @param string $taskName
     * @param string $mappingName
     *
     * @return array
     */
    public function match(string $taskName, string $mappingName): array;
}