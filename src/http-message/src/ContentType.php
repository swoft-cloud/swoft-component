<?php declare(strict_types=1);

namespace Swoft\Http\Message;

/**
 * Class ContentType
 * @package Swoft\Http\Message
 */
final class ContentType
{
    public const KEY = 'Content-Type';

    // Commonly used content types
    public const HTML = 'text/html';
    public const TEXT = 'text/plain';
    public const JSON = 'application/json';
    public const XML  = 'application/xml';
    public const FORM = 'application/x-www-form-urlencoded';

    // Content types mapping
    public const TYPES = [
        'xml'  => self::XML,
        'html' => self::HTML,
        'text' => self::TEXT,
        'json' => self::JSON,
        'form' => self::FORM,
    ];
}
