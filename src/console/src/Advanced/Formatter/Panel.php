<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Advanced\Formatter;

use Swoft\Console\Advanced\MessageFormatter;
use Swoft\Console\Console;
use Swoft\Console\Helper\FormatUtil;
use Swoft\Stdlib\Helper\Str;
use Swoft\Stdlib\StrBuffer;
use function array_filter;
use function array_merge;
use function ceil;
use function is_array;
use function is_bool;
use function is_numeric;
use function rtrim;
use function strip_tags;
use function trim;
use function ucwords;
use const PHP_EOL;

/**
 * Class Panel
 * @package Swoft\Console\Advanced\Formatter
 */
class Panel extends MessageFormatter
{
    /** @var string */
    public $title = '';

    /** @var string */
    public $titleBorder = '-';

    /** @var string */
    public $titleStyle = 'bold';

    /** @var string */
    public $titleAlign = self::ALIGN_LEFT;

    /** @var string|array */
    public $data;

    /** @var string */
    public $bodyAlign = self::ALIGN_LEFT;

    /** @var string */
    public $footerBorder = '-';

    /** @var string */
    public $footer = '';

    /** @var bool */
    public $ucFirst = true;

    /** @var int */
    public $width = 0;

    /** @var bool */
    public $showBorder = true;

    /** @var string */
    public $borderYChar = '-';

    /** @var string */
    public $borderXChar = '|';

    /**
     * @var string Template for the panel. don't contains border
     */
    public $template = <<<EOF
{%title%}
{%title-border%}
{%content%}
{%footer-border%}
{%footer%}
EOF;

    /**
     * Show information data panel
     * @param  mixed  $data
     * @param  string $title
     * @param  array  $opts
     * @return int
     */
    public static function show($data, string $title = 'Information Panel', array $opts = []): int
    {
        if (!$data) {
            Console::write('<info>No data to display!</info>');
            return -2;
        }

        $opts = array_merge([
            'borderChar' => '*',
            'sepChar'    => ' | ',
            'ucFirst'    => true,
            'titleStyle' => 'bold',
            'leftIndent' => '  ',
        ], $opts);

        $data  = is_array($data) ? array_filter($data) : [trim($data)];
        $title = trim($title);

        $panelData  = []; // [ 'label' => 'value' ]
        $borderChar = $opts['borderChar'];
        $leftIndent = $opts['leftIndent'];

        $labelMaxWidth = 0; // if label exists, label max width
        $valueMaxWidth = 0; // value max width

        foreach ($data as $label => $value) {
            // label exists
            if (!is_numeric($label)) {
                $width = Str::len($label, 'UTF-8');
                // save max value
                $labelMaxWidth = $width > $labelMaxWidth ? $width : $labelMaxWidth;
            }

            // translate array to string
            if (is_array($value)) {
                $temp = '';

                /** @var array $value */
                foreach ($value as $key => $val) {
                    if (is_bool($val)) {
                        $val = $val ? 'True' : 'False';
                    } else {
                        $val = (string)$val;
                    }

                    $temp .= (!is_numeric($key) ? "$key: " : '') . "<info>$val</info>, ";
                }

                $value = rtrim($temp, ' ,');
            } elseif (is_bool($value)) {
                $value = $value ? 'True' : 'False';
            } else {
                $value = trim((string)$value);
            }

            // get value width
            /** @var string $value */
            $value = trim($value);
            $width = Str::len(strip_tags($value), 'UTF-8'); // must clear style tag

            $valueMaxWidth     = $width > $valueMaxWidth ? $width : $valueMaxWidth;
            $panelData[$label] = $value;
        }

        $border     = null;
        $panelWidth = $labelMaxWidth + $valueMaxWidth;

        $opts['leftChar'] = $leftIndent . $borderChar . ' ';
        $opts['keyMaxWidth'] = $labelMaxWidth;

        Console::startBuffer();

        // output title
        if ($title) {
            $title       = ucwords($title);
            $titleStyle  = $opts['titleStyle'] ?: 'bold';
            $titleLength = Str::len($title, 'UTF-8');
            $panelWidth  = $panelWidth > $titleLength ? $panelWidth : $titleLength;
            $lenValue    = (int)(ceil($panelWidth / 2) - ceil($titleLength / 2));
            $indentSpace = Str::pad(' ', $lenValue + 2 * 2, ' ');
            Console::write("$leftIndent{$indentSpace}<{$titleStyle}>{$title}</{$titleStyle}>");
        }

        // output panel top border
        if ($borderChar) {
            $border = Str::pad($borderChar, $panelWidth + (3 * 4), $borderChar);
            Console::write($leftIndent . $border);
        }

        // output panel body
        $panelContent = FormatUtil::spliceKeyValue($panelData, $opts);

        // already exists "\n"
        Console::write($panelContent, false);

        // output panel bottom border
        if ($border) {
            Console::write("{$leftIndent}{$border}\n");
        }

        Console::flushBuffer();
        unset($panelData);
        return 0;
    }

    /**
     * @return string
     */
    public function format(): string
    {
        if (!$this->data) {
            // self::write('<info>No data to display!</info>');
            return '';
        }

        $buffer = new StrBuffer();
        $data   = is_array($this->data) ? array_filter($this->data) : [trim($this->data)];
        $title  = trim($this->title);

        $panelData  = []; // [ 'label' => 'value' ]
        $borderChar = $this->borderXChar;

        $labelMaxWidth = 0; // if label exists, label max width
        $valueMaxWidth = 0; // value max width

        foreach ($data as $label => $value) {
            // label exists
            if (!is_numeric($label)) {
                $width         = Str::len($label, 'UTF-8');
                $labelMaxWidth = $width > $labelMaxWidth ? $width : $labelMaxWidth;
            }

            // translate array to string
            if (is_array($value)) {
                $temp = '';

                /** @var array $value */
                foreach ($value as $key => $val) {
                    if (is_bool($val)) {
                        $val = $val ? 'True' : 'False';
                    } else {
                        $val = (string)$val;
                    }

                    $temp .= (!is_numeric($key) ? "$key: " : '') . "<info>$val</info>, ";
                }

                $value = rtrim($temp, ' ,');
            } elseif (is_bool($value)) {
                $value = $value ? 'True' : 'False';
            } else {
                $value = trim((string)$value);
            }

            // get value width
            /** @var string $value */
            $value = trim($value);
            $width = Str::len(strip_tags($value), 'UTF-8'); // must clear style tag

            $valueMaxWidth     = $width > $valueMaxWidth ? $width : $valueMaxWidth;
            $panelData[$label] = $value;
        }

        $panelWidth = $labelMaxWidth + $valueMaxWidth;

        // output title
        if ($title) {
            $title       = ucwords($title);
            $titleLength = Str::len($title, 'UTF-8');
            $panelWidth  = $panelWidth > $titleLength ? $panelWidth : $titleLength;
            $indentSpace = Str::pad(' ', ceil($panelWidth / 2) - ceil($titleLength / 2) + 2 * 2, ' ');
            $buffer->write("  {$indentSpace}<bold>{$title}</bold>\n");
        }

        // output panel top border
        if ($topBorder = $this->titleBorder) {
            $border = Str::pad($topBorder, $panelWidth + (3 * 3), $topBorder);
            $buffer->write('  ' . $border . PHP_EOL);
        }

        // output panel body
        $panelStr = FormatUtil::spliceKeyValue($panelData, [
            'ucFirst'     => $this->ucFirst,
            'leftChar'    => "  $borderChar ",
            'sepChar'     => ' | ',
            'keyMaxWidth' => $labelMaxWidth,
        ]);

        // already exists "\n"
        $buffer->write($panelStr);

        // output panel bottom border
        if ($footBorder = $this->footerBorder) {
            $border = Str::pad($footBorder, $panelWidth + (3 * 3), $footBorder);
            $buffer->write('  ' . $border . PHP_EOL);
        }

        unset($panelData);
        return $buffer->toString();
    }

    /**
     * @param bool $border
     * @return $this
     */
    public function showBorder($border): self
    {
        $this->showBorder = (bool)$border;
        return $this;
    }
}
