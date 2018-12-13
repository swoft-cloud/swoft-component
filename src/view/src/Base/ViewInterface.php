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

namespace Swoft\View\Base;

/**
 * the interface of view
 *
 * @uses      ViewInterface
 */
interface ViewInterface
{
    /**
     * @param string      $view
     * @param array       $data
     * @param string|null|false $layout
     * @return string
     */
    public function render(string $view, array $data = [], $layout = null): string ;
}
