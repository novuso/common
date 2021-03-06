<?php

declare(strict_types=1);

namespace Novuso\Common\Application\FileStorage\Exception;

use Throwable;

/**
 * Class DuplicateStorageException
 */
class DuplicateStorageException extends FileStorageException
{
    /**
     * Constructs DuplicateStorageException
     */
    public function __construct(
        string $message,
        protected ?string $key = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Creates exception for a given storage key
     */
    public static function fromKey(
        string $key,
        ?Throwable $previous = null
    ): static {
        $message = sprintf('Duplicate storage: %s', $key);

        return new static($message, $key, $previous);
    }

    /**
     * Retrieves the storage key
     */
    public function getKey(): ?string
    {
        return $this->key;
    }
}
