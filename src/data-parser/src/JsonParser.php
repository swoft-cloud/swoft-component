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

namespace Swoft\DataParser;

/**
 * Class JsonParser
 * @package Swoft\DataParser
 * @author inhere <in.798@qq.com>
 */
class JsonParser implements ParserInterface
{
    /**
     * @var bool
     */
    protected $assoc = true;

    /**
     * JsonParser constructor.
     * @param null $assoc
     */
    public function __construct($assoc = null)
    {
        if ($assoc !== null) {
            $this->setAssoc($assoc);
        }
    }

    /**
     * @param mixed $data
     * @return string
     */
    public function encode($data): string
    {
        return \json_encode($data);
    }

    /**
     * @param string $data
     * @return mixed
     */
    public function decode(string $data)
    {
        return \json_decode($data, $this->assoc);
    }

    /**
     * @return bool
     */
    public function isAssoc(): bool
    {
        return $this->assoc;
    }

    /**
     * @param bool $assoc
     */
    public function setAssoc($assoc)
    {
        $this->assoc = (bool)$assoc;
    }
}
