<?php
declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean;

/**
 * Annotaions Data Collector Interface
 */
interface CollectorInterface
{
    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    );

    public static function getCollector(): array;
}
