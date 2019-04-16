<?php declare(strict_types=1);

namespace Swoft\Stdlib\Helper;

/**
 * Class FileHelper
 */
class FileHelper extends FSHelper
{
    /**
     * Get file extension, suffix name
     * @param string $filename
     * @param bool $clearPoint
     * @return string
     */
    public static function getSuffix(string $filename, bool $clearPoint = false): string
    {
        if (!$suffix = \strrchr($filename, '.')) {
            return '';
        }

        return $clearPoint ? \rtrim($suffix, '.') : $suffix;
    }

    /**
     * Get file extension, suffix name
     * @param string $path
     * @param bool $clearPoint
     * @return string
     */
    public static function getExtension(string $path, bool $clearPoint = false): string
    {
        $ext = \pathinfo($path, \PATHINFO_EXTENSION);

        return $clearPoint ? $ext : '.' . $ext;
    }

    /**
     * @param string $file
     * @return string eg: image/gif
     */
    public static function mimeType($file): string
    {
        if (\function_exists('finfo_file')) {
            return \finfo_file(\finfo_open(\FILEINFO_MIME_TYPE), $file);
        }

        return '';
    }

}
