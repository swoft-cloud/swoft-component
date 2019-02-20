<?php

namespace Swoft\Stdlib\Helper;

/**
 * Directory helper
 *
 * @since 2.0
 */
class DirectoryHelper
{
    /**
     * Create directory
     *
     * @param string $dir
     * @param integer $mode
     * @return void
     */
    public static function make(string $dir, int $mode = 0755): void
    {
        if (!\file_exists($dir) && !\mkdir($dir, $mode, true) && !\is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }

    /**
     * Directory iterator
     *
     * @param string $path
     * @param int    $iteratorFlags
     * @param int    $mode
     * @param int    $flags
     * @return \RecursiveIteratorIterator
     */
    public static function iterator(
        string $path,
        int $iteratorFlags = \FilesystemIterator::KEY_AS_PATHNAME,
        int $mode = \RecursiveIteratorIterator::LEAVES_ONLY,
        int $flags = 0
    ): \RecursiveIteratorIterator {
        if (empty($path) || !file_exists($path)) {
            throw new \InvalidArgumentException('File path is not exist! Path: ' . $path);
        }
        $directoryIterator = new \RecursiveDirectoryIterator($path);
        $iterator          = new \RecursiveIteratorIterator($directoryIterator, $mode, $flags);

        return $iterator;
    }


    /**
     * Directory iterator but support filter files.
     * @param string $srcDir
     * @param callable $filter
     * eg: only find php file
     * $filter = function (\SplFileInfo $f): bool {
     *      $name = $f->getFilename();
     *
     *       // Skip hidden files and directories.
     *      if (\strpos($name, '.') === 0) {
     *          return false;
     *      }
     *
     *      // go on read sub-dir
     *      if ($f->isDir()) {
     *          return true;
     *      }
     *
     *      // php file
     *      return $f->isFile() && \substr($name, -4) === '.php';
     * }
     * @param int $flags
     * @return \RecursiveIteratorIterator
     * @throws \InvalidArgumentException
     */
    public static function filterIterator(
        string $srcDir,
        callable $filter,
        $flags = \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO
    ): \RecursiveIteratorIterator
    {
        if (!$srcDir || !\file_exists($srcDir)) {
            throw new \InvalidArgumentException('Please provide a exists source directory. Path:' . $srcDir);
        }

        $directory = new \RecursiveDirectoryIterator($srcDir, $flags);
        $filterIterator = new \RecursiveCallbackFilterIterator($directory, $filter);

        return new \RecursiveIteratorIterator($filterIterator);
    }
}