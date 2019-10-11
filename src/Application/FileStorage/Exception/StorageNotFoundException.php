<?php declare(strict_types=1);

namespace Novuso\Common\Application\FileStorage\Exception;

use Throwable;

/**
 * Class StorageNotFoundException
 */
class StorageNotFoundException extends FileStorageException
{
    /**
     * Storage key
     *
     * @var string|null
     */
    protected $key;

    /**
     * Constructs StorageNotFoundException
     *
     * @param string         $message  The message
     * @param string|null    $key      The storage key
     * @param Throwable|null $previous The previous exception
     */
    public function __construct(string $message, ?string $key = null, ?Throwable $previous = null)
    {
        $this->key = $key;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Creates exception for a given storage key
     *
     * @param string         $key      The storage key
     * @param Throwable|null $previous The previous exception
     *
     * @return StorageNotFoundException
     */
    public static function fromKey(string $key, ?Throwable $previous = null): StorageNotFoundException
    {
        $message = sprintf('Storage not found: %s', $key);

        return new static($message, $key, $previous);
    }

    /**
     * Retrieves the storage key
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }
}
