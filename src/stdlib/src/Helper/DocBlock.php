<?php declare(strict_types=1);


namespace Swoft\Stdlib\Helper;

use ReflectionException;
use ReflectionProperty;

/**
 * Class DocBlock
 *
 * @since 2.0
 */
class DocBlock
{
    /**
     * Parses the comment block into tags.
     *
     * @param string $comment The comment block text
     * @param array  $options
     *                        - 'allow'  // only allowed tags
     *                        - 'ignore' // ignored tags
     *                        - 'default' => 'description', // default tag name, first line text will attach to it.
     * @param array  $defaults
     *
     * @return array The parsed tags
     */
    public static function getTags(string $comment, array $options = [], array $defaults = []): array
    {
        if (!$comment = trim($comment, "/ \n")) {
            return [];
        }

        $options = array_merge([
            'allow'   => [], // only allowed tags
            'ignore'  => ['param', 'return'], // ignore tags
            'default' => 'description', // default tag name, first line text will attach to it.
        ], $options);

        $allow   = (array)$options['allow'];
        $ignored = (array)$options['ignore'];
        $default = (string)$options['default'];

        $comment = str_replace("\r\n", "\n", $comment);
        $comment = "@{$default} \n" .
            str_replace("\r", '',
                trim(preg_replace('/^\s*\**( |\t)?/m', '', $comment))
            );

        $tags  = [];
        $parts = preg_split('/^\s*@/m', $comment, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($parts as $part) {
            if (preg_match('/^(\w+)(.*)/ms', trim($part), $matches)) {
                $name = $matches[1];
                if (!$name || in_array($name, $ignored, true)) {
                    continue;
                }

                if (!$value = trim($matches[2])) {
                    continue;
                }

                // always allow default tag
                if ($default !== $name && $allow && !in_array($name, $allow, true)) {
                    continue;
                }

                if (!isset($tags[$name])) {
                    $tags[$name] = $value;
                } elseif (is_array($tags[$name])) {
                    $tags[$name][] = $value;
                } else {
                    $tags[$name] = [$tags[$name], $value];
                }
            }
        }

        return $defaults ? array_merge($defaults, $tags) : $tags;
    }

    /**
     * Returns the first line of docBlock.
     *
     * @param string $comment
     *
     * @return string
     */
    public static function firstLine(string $comment): string
    {
        $docLines = preg_split('~\R~u', $comment);

        if (isset($docLines[1])) {
            return trim($docLines[1], "/\t *");
        }

        return '';
    }

    /**
     * Returns full description from the doc-block.
     * If have multi line text, will return multi line.
     *
     * @param string $comment
     *
     * @return string
     */
    public static function description(string $comment): string
    {
        $comment = str_replace("\r", '', trim(preg_replace('/^\s*\**( |\t)?/m', '', trim($comment, '/'))));

        if (preg_match('/^\s*@\w+/m', $comment, $matches, PREG_OFFSET_CAPTURE)) {
            $comment = trim(substr($comment, 0, $matches[0][1]));
        }

        return $comment;
    }

    /**
     * @param string $className
     * @param string $propertyName
     *
     * @return string
     * @throws ReflectionException
     */
    public static function getPropertyDescription(string $className, string $propertyName)
    {
        // Parse php document
        $reflectProperty = new ReflectionProperty($className, $propertyName);
        $description     = DocBlock::description($reflectProperty->getDocComment());

        if (empty($description)) {
            return '';
        }

        return $description;
    }
}