<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-02
 * Time: 18:02
 */

namespace Swoft\Helper;

use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Class ComposerJSONHelper
 * @package Swoft\Helper
 */
class ComposerJSON
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param string $jsonFile
     * @return ComposerJSON
     */
    public static function open(string $jsonFile): self
    {
        return new self($jsonFile);
    }

    /**
     * Class constructor.
     * @param string $jsonFile
     */
    public function __construct(string $jsonFile)
    {
        if (!\file_exists($jsonFile)) {
            throw new \InvalidArgumentException('composer.json file is not exist! FILE: ' . $jsonFile);
        }

        $content = \file_get_contents($jsonFile);
        $this->data = \json_decode($content, true);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return ArrayHelper::get($this->data, $key);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function clear(): void
    {
        $this->data = [];
    }
}
