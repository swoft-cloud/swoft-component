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
namespace Swoft\Bean\Wrapper\Extend;

/**
 * Wrapper extend
 */
interface WrapperExtendInterface
{
    public function getClassAnnotations(): array;

    public function getPropertyAnnotations(): array;

    public function getMethodAnnotations(): array;

    public function isParseClassAnnotations(array $annotations): bool;

    public function isParsePropertyAnnotations(array $annotations): bool;

    public function isParseMethodAnnotations(array $annotations): bool;
}
