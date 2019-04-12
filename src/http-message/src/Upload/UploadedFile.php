<?php declare(strict_types=1);

namespace Swoft\Http\Message\Upload;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Class UploadedFile
 *
 * @since 2.0
 */
class UploadedFile implements UploadedFileInterface
{
    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $errorCode;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $clientFilename;

    /**
     * @var string
     */
    private $clientMediaType;

    /**
     * @param string $tmpFile
     * @param int    $size
     * @param int    $errorStatus
     * @param string $clientFilename
     * @param string $clientMediaType
     */
    public function __construct(
        string $tmpFile,
        int $size,
        int $errorStatus,
        string $clientFilename = '',
        string $clientMediaType = ''
    ) {
        $this->setError($errorStatus)
            ->setSize($size)
            ->setClientFilename($clientFilename)
            ->setClientMediaType($clientMediaType);

        $this->isOk() && $this->setFile($tmpFile);
    }

    /**
     * Retrieve a stream representing the uploaded file.
     *
     * This method MUST return a StreamInterface instance, representing the
     * uploaded file. The purpose of this method is to allow utilizing native PHP
     * stream functionality to manipulate the file upload, such as
     * stream_copy_to_stream() (though the result will need to be decorated in a
     * native PHP stream wrapper to work with such functions).
     *
     * If the moveTo() method has been called previously, this method MUST raise
     * an exception.
     *
     * @return StreamInterface Stream representation of the uploaded file.
     * @throws \RuntimeException in cases when no stream is available or can be
     *     created.
     */
    public function getStream()
    {
        // TODO ...
    }

    /**
     * Move the uploaded file to a new location.
     *
     * @inheritdoc
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     *
     * @param string $targetPath Path to which to move the uploaded file.
     *
     * @throws \InvalidArgumentException if the $targetPath specified is invalid.
     * @throws \RuntimeException on any error during the move operation, or on
     *     the second or subsequent call to the method.
     */
    public function moveTo($targetPath): void
    {
        // TODO ...
    }

    /**
     * Retrieve the file size.
     *
     * Implementations SHOULD return the value stored in the "size" key of
     * the file in the $_FILES array if available, as PHP calculates this based
     * on the actual size transmitted.
     *
     * @return int|null The file size in bytes or null if unknown.
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * The return value MUST be one of PHP's UPLOAD_ERR_XXX constants.
     *
     * If the file was uploaded successfully, this method MUST return
     * UPLOAD_ERR_OK.
     *
     * Implementations SHOULD return the value stored in the "error" key of
     * the file in the $_FILES array.
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     * @return int One of PHP's UPLOAD_ERR_XXX constants.
     */
    public function getError(): int
    {
        return $this->errorCode;
    }

    /**
     * Retrieve the filename sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious filename with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "name" key of
     * the file in the $_FILES array.
     *
     * @return string|null The filename sent by the client or null if none
     *     was provided.
     */
    public function getClientFilename(): string
    {
        return $this->clientFilename;
    }

    /**
     * Retrieve the media type sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious media type with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "type" key of
     * the file in the $_FILES array.
     *
     * @return string|null The media type sent by the client or null if none
     *     was provided.
     */
    public function getClientMediaType(): string
    {
        return $this->clientMediaType;
    }

    /**
     * @param int $errorCode
     * @return UploadedFile
     */
    public function setError(int $errorCode): self
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @param string $tmpFile
     * @return UploadedFile
     */
    private function setFile(string $tmpFile): self
    {
        $this->file = $tmpFile;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->errorCode === \UPLOAD_ERR_OK;
    }

    /**
     * @param int $size
     * @return UploadedFile
     */
    public function setSize(int $size): UploadedFile
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @param string $clientFilename
     * @return UploadedFile
     */
    public function setClientFilename(string $clientFilename): UploadedFile
    {
        $this->clientFilename = $clientFilename;
        return $this;
    }

    /**
     * @param string $clientMediaType
     * @return UploadedFile
     */
    public function setClientMediaType(string $clientMediaType): UploadedFile
    {
        $this->clientMediaType = $clientMediaType;
        return $this;
    }
}
