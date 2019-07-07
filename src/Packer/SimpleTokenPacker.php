<?php declare(strict_types=1);

namespace Swoft\Tcp\Protocol\Packer;

use function explode;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Tcp\Protocol\Contract\PackerInterface;
use Swoft\Tcp\Protocol\Package;
use function trim;

/**
 * Class SimpleTokenPacker
 *
 * @since 2.0.3
 * @Bean()
 */
class SimpleTokenPacker implements PackerInterface
{
    public const TYPE = 'token-text';

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }

    /**
     * Encode Package object to string data.
     *
     * @param Package $package
     *
     * @return string
     */
    public function encode(Package $package): string
    {
        return (string)$package->getData();
    }

    /**
     * Decode tcp package data to Package object
     *
     * @param string $data package data, use first space to split cmd and data.
     * Format like:
     *      login message text
     *  =>
     *      cmd: 'login'
     *      data: 'message text'
     *
     * @return Package
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function decode(string $data): Package
    {
        // Use default message command
        $cmd  = '';
        $data = trim($data);

        if (strpos($data, ' ')) {
            [$cmd, $body] = explode(' ', $data, 2);
            $cmd = trim($cmd);
        } else {
            $body = $data;
        }

        return Package::new($cmd, $body);
    }
}
