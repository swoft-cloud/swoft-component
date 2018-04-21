<?php

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
