<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Http\Message;

/**
 * Class ContentType
 *
 * @since 2.0
 */
final class ContentType
{
    // Content type key on http headers
    public const KEY = 'content-type';

    // Commonly used content types
    public const XML  = 'text/xml';

    public const HTML = 'text/html';

    public const TEXT = 'text/plain';

    public const JSON = 'application/json';

    public const FORM = 'application/x-www-form-urlencoded';

    // For upload file
    public const FORM_DATA = 'multipart/form-data';

    // Content types mapping
    public const TYPES = [
        'xml'  => self::XML,
        'html' => self::HTML,
        'text' => self::TEXT,
        'json' => self::JSON,
        'form' => self::FORM,
    ];
}
