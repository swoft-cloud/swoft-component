<?php declare(strict_types=1);

namespace Swoft\Tcp\Protocol;

use Swoft;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Tcp\Protocol\Contract\PackerInterface;
use Swoft\Tcp\Protocol\Exception\ProtocolException;
use Swoft\Tcp\Protocol\Packer\JsonPacker;
use Swoft\Tcp\Protocol\Packer\SimpleTokenPacker;
use function array_keys;
use function array_merge;

/**
 * Class PackerFactory
 *
 * @since 2.0.3
 */
class Protocol
{
    /**
     * The default packers
     */
    public const DEFAULT_PACKERS = [
        JsonPacker::TYPE        => JsonPacker::class,
        SimpleTokenPacker::TYPE => SimpleTokenPacker::class,
    ];

    /**
     * The default packer type name
     *
     * @var string
     */
    private $type = JsonPacker::TYPE;

    /**
     * The available data packers
     *
     * @var array
     * [
     *  type name => packet bean name(PackerInterface)
     * ]
     */
    private $packers;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // Ensure packers always available
        $this->packers = self::DEFAULT_PACKERS;
    }

    /**
     * @param Package $package
     *
     * @return string
     * @throws ContainerException
     */
    public function encode(Package $package): string
    {
        return $this->getPacker()->encode($package);
    }

    /**
     * @param string $data
     *
     * @return Package
     * @throws ContainerException
     */
    public function decode(string $data): Package
    {
        return $this->getPacker()->decode($data);
    }

    /**
     * @param Package $package
     *
     * @return string
     * @throws ContainerException
     */
    public function packing(Package $package): string
    {
        return $this->encode($package);
    }

    /**
     * @param string $data
     *
     * @return Package
     * @throws ContainerException
     */
    public function unpacking(string $data): Package
    {
        return $this->decode($data);
    }

    /**
     * @param string $type
     *
     * @return PackerInterface
     * @throws ContainerException
     */
    public function getPacker(string $type = ''): PackerInterface
    {
        $class  = $this->getPackerClass($type ?: $this->type);
        $packer = Swoft::getSingleton($class);

        if (!$packer instanceof PackerInterface) {
            throw new ProtocolException("The data packer '{$class}' must be implements PackerInterface");
        }

        return $packer;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getPackerClass(string $type = ''): string
    {
        $type = $type ?: $this->type;
        if (isset($this->packers[$type])) {
            throw new ProtocolException("The data packer is not exist! type: $type");
        }

        return $this->packers[$type];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $type
     * @param string $packerClass
     */
    public function setPacker(string $type, string $packerClass): void
    {
        $this->packers[$type] = $packerClass;
    }

    /**
     * @return array
     */
    public function getPackers(): array
    {
        return $this->packers;
    }

    /**
     * @param array $packers
     */
    public function setPackers(array $packers): void
    {
        $this->packers = array_merge($this->packers, $packers);
    }

    /**
     * @return array
     */
    public function getPackerNames(): array
    {
        return array_keys($this->packers);
    }
}
