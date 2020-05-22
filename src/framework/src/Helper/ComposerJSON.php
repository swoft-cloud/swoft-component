<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Helper;

use InvalidArgumentException;
use Swoft\Stdlib\Helper\ArrayHelper;
use function file_exists;
use function file_get_contents;
use function json_decode;

/**
 * Class ComposerJSONHelper
 *
 * @since 2.0.0
 */
class ComposerJSON
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param string $jsonFile
     *
     * @return ComposerJSON
     */
    public static function open(string $jsonFile): self
    {
        return new self($jsonFile);
    }

    /**
     * Class constructor.
     *
     * @param string $jsonFile
     */
    public function __construct(string $jsonFile)
    {
        if (!file_exists($jsonFile)) {
            throw new InvalidArgumentException('composer.json is not exist! FILE: ' . $jsonFile);
        }

        $content = file_get_contents($jsonFile);
        // decode
        $this->data = json_decode($content, true);
    }

    /**
     * get a component metadata from package composer.json
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return [
            'name'        => $this->data['name'] ?? 'Unknown',
            // 'title'       => $this->data['name'] ?? 'Unknown',
            'authors'     => $this->data['authors'] ?? [],
            'license'     => $this->data['license'] ?? 'Unknown',
            'version'     => $this->data['version'] ?? '1.0.0',
            // 'createAt'    => '2019.02.12',
            // 'updateAt'    => '2019.04.12',
            'description' => $this->data['description'] ?? 'no description message',
            'homepage'    => $this->data['homepage'] ?? '',
            'keywords'    => $this->data['keywords'] ?? [],
        ];
    }

    /**
     * @param string $key
     *
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
