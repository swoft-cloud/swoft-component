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
namespace Swoft\Validator;

/**
 * Interface ValidatorInterface
 *
 * @package Swoft\Validator
 */
interface ValidatorInterface
{
    /**
     * @param array ...$params
     * @return mixed
     */
    public function validate(...$params);
}
