<?php

namespace Swoft\Console\Formatter;

use Swoft\Console\Helper\FormatUtil;
use Swoft\Console\Helper\Show;
use Toolkit\Cli\ColorTag;

/**
 * Class SingleList - Format and render a single list
 * @package Swoft\Console\Formatter
 */
class SingleList extends Formatter
{
    /**
     * Format and render a single list
     *
     * ```php
     * $title = 'list title';
     * $data = [
     *      'name'  => 'value text',
     *      'name2' => 'value text 2',
     * ];
     * ```
     *
     * @param array  $data
     * @param string $title
     * @param array  $opts More {@see FormatUtil::spliceKeyValue()}
     * @return int|string
     */
    public static function show($data, string $title = '', array $opts = [])
    {
        $string = '';
        $opts   = \array_merge([
            'leftChar'    => '  ',
            // 'sepChar' => '  ',
            'keyStyle'    => 'info',
            'keyMinWidth' => 8,
            'titleStyle'  => 'comment',
            'returned'    => false,
            'lastNewline' => true,
        ], $opts);

        // title
        if ($title) {
            $title  = \ucwords(\trim($title));
            $string .= ColorTag::wrap($title, $opts['titleStyle']) . \PHP_EOL;
        }

        // handle item list
        $string .= FormatUtil::spliceKeyValue((array)$data, $opts);

        // return formatted string.
        if ($opts['returned']) {
            return $string;
        }

        return Show::write($string, $opts['lastNewline']);
    }
}
