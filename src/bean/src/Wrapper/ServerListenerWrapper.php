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
namespace Swoft\Bean\Wrapper;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\ServerListener;

class ServerListenerWrapper extends AbstractWrapper
{
    protected $classAnnotations = [
        ServerListener::class,
    ];

    protected $propertyAnnotations = [
        Inject::class,
    ];

    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[ServerListener::class]);
    }

    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Inject::class]);
    }

    public function isParseMethodAnnotations(array $annotations): bool
    {
        return false;
    }
}
