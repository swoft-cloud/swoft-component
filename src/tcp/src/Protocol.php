<?php declare(strict_types=1);

namespace Swoft\Tcp;

use Swoft;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Tcp\Contract\PackerInterface;
use Swoft\Tcp\Exception\ProtocolException;
use Swoft\Tcp\Packer\JsonPacker;
use Swoft\Tcp\Packer\SimpleTokenPacker;
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
     * @var int
     */
    private $packageMaxLength = 81920;

    // ------- use package length check

    /**
     * Open package length check
     *
     * swoole.setting => [
     *  'package_max_length'    => 81920,
     *  'open_length_check'     => true,
     *  'package_length_type'   => 'N',
     *  'package_length_offset' => 8,
     *  'package_body_offset'   => 16,
     * ]
     *          8-11 length
     *            |
     * [0===4===8===12===16|BODY...]
     *
     * @link https://wiki.swoole.com/wiki/page/287.html
     * @var bool
     */
    private $openLengthCheck = false;

    /**
     * @link https://wiki.swoole.com/wiki/page/463.html
     * @var string
     */
    private $packageLengthType = 'N';

    /**
     * The Nth byte is the value of the packet length
     *
     * @var int
     */
    private $packageLengthOffset = 8;

    /**
     * The first few bytes start to calculate the length
     *
     * @var int
     */
    private $packageBodyOffset = 16;

    // ------- use package eof check

    /**
     * Open package EOF check
     *
     * swoole.setting => [
     *  'package_max_length' => 81920,
     *  'open_eof_check'     => true,
     *  'open_eof_split'     => true,
     *  'package_eof'        => "\r\n\r\n",
     * ]
     *
     * @link https://wiki.swoole.com/wiki/page/285.html
     * @var bool
     */
    private $openEofCheck = true;

    /**
     * @var bool
     */
    private $openEofSplit = false;

    /**
     * @var string
     */
    private $packageEof = "\r\n\r\n";

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // Ensure packers always available
        $this->packers = self::DEFAULT_PACKERS;
    }

    /*********************************************************************
     * (Un)Packing data for server
     ********************************************************************/

    /**
     * Unpacking the client request data as an [Package]
     *
     * @param string $data
     *
     * @return Package
     * @throws ContainerException
     */
    public function unpack(string $data): Package
    {
        return $this->getPacker()->decode($data);
    }

    /**
     * Packing [Response] to string for response client
     *
     * @param Response $response
     *
     * @return string
     * @throws ContainerException
     */
    public function packResponse(Response $response): string
    {
        return $this->getPacker()->encodeResponse($response);
    }

    /*********************************************************************
     * (Un)Packing data for client
     ********************************************************************/

    /**
     * Unpacking the server response data as an [Response]
     *
     * @param string $data
     *
     * @return Response
     * @throws ContainerException
     */
    public function unpackResponse(string $data): Response
    {
        return $this->getPacker()->decodeResponse($data);
    }

    /**
     * Packing [Package] to string for request server
     *
     * @param Package $package
     *
     * @return string
     * @throws ContainerException
     */
    public function pack(Package $package): string
    {
        return $this->getPacker()->encode($package);
    }

    /*********************************************************************
     * Getter/Setter methods
     ********************************************************************/

    /**
     * Get data packer instance
     *
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

        if (!isset($this->packers[$type])) {
            throw new ProtocolException("The data packer(type: $type) is not exist! ");
        }

        return $this->packers[$type];
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        // Use EOF check
        if ($this->openEofCheck) {
            return [
                'open_eof_check'     => true,
                'open_eof_split'     => $this->openEofSplit,
                'package_eof'        => $this->packageEof,
                'package_max_length' => $this->packageMaxLength,
                'open_length_check'  => false,
            ];
        }

        // Use length check
        return [
            'open_length_check'     => true,
            'package_length_type'   => $this->packageLengthType,
            'package_length_offset' => $this->packageLengthOffset,
            'package_body_offset'   => $this->packageBodyOffset,
            'package_max_length'    => $this->packageMaxLength,
            'open_eof_check'        => false,
        ];
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

    /**
     * @return bool
     */
    public function isOpenLengthCheck(): bool
    {
        return $this->openLengthCheck;
    }

    /**
     * @param bool $openLengthCheck
     */
    public function setOpenLengthCheck($openLengthCheck): void
    {
        $this->openLengthCheck = (bool)$openLengthCheck;
    }

    /**
     * @return string
     */
    public function getPackageLengthType(): string
    {
        return $this->packageLengthType;
    }

    /**
     * @param string $packageLengthType
     */
    public function setPackageLengthType(string $packageLengthType): void
    {
        $this->packageLengthType = $packageLengthType;
    }

    /**
     * @return int
     */
    public function getPackageLengthOffset(): int
    {
        return $this->packageLengthOffset;
    }

    /**
     * @param int $packageLengthOffset
     */
    public function setPackageLengthOffset(int $packageLengthOffset): void
    {
        $this->packageLengthOffset = $packageLengthOffset;
    }

    /**
     * @return int
     */
    public function getPackageBodyOffset(): int
    {
        return $this->packageBodyOffset;
    }

    /**
     * @param int $packageBodyOffset
     */
    public function setPackageBodyOffset(int $packageBodyOffset): void
    {
        $this->packageBodyOffset = $packageBodyOffset;
    }

    /**
     * @return bool
     */
    public function isOpenEofCheck(): bool
    {
        return $this->openEofCheck;
    }

    /**
     * @param bool $openEofCheck
     */
    public function setOpenEofCheck($openEofCheck): void
    {
        $this->openEofCheck = (bool)$openEofCheck;
    }

    /**
     * @return bool
     */
    public function isOpenEofSplit(): bool
    {
        return $this->openEofSplit;
    }

    /**
     * @param bool $openEofSplit
     */
    public function setOpenEofSplit($openEofSplit): void
    {
        $this->openEofSplit = (bool)$openEofSplit;
    }

    /**
     * @return string
     */
    public function getPackageEof(): string
    {
        return $this->packageEof;
    }

    /**
     * @param string $packageEof
     */
    public function setPackageEof(string $packageEof): void
    {
        $this->packageEof = $packageEof;
    }

    /**
     * @return int
     */
    public function getPackageMaxLength(): int
    {
        return $this->packageMaxLength;
    }

    /**
     * @param int $packageMaxLength
     */
    public function setPackageMaxLength(int $packageMaxLength): void
    {
        $this->packageMaxLength = $packageMaxLength;
    }
}
