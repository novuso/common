<?php

declare(strict_types=1);

namespace Novuso\Common\Application\FileTransfer;

use Novuso\Common\Application\FileTransfer\Exception\FileTransferException;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;

/**
 * Class FileTransferService
 */
final class FileTransferService
{
    protected HashTable $transports;

    /**
     * Constructs FileTransferService
     */
    public function __construct()
    {
        $this->transports = HashTable::of('string', FileTransport::class);
    }

    /**
     * Retrieves a transport by key
     *
     * @throws KeyException When the transport is not found
     */
    public function getTransport(string $key): FileTransport
    {
        if (!$this->transports->has($key)) {
            $message = sprintf('FileTransport %s not found', $key);
            throw new KeyException($message);
        }

        return $this->transports->get($key);
    }

    /**
     * Adds file transport
     *
     * @throws FileTransferException When the key is already in use
     */
    public function addTransport(string $key, FileTransport $transport): void
    {
        if ($this->transports->has($key)) {
            $message = sprintf('Duplicate transport: %s', $key);
            throw new FileTransferException($message);
        }

        $this->transports->set($key, $transport);
    }
}
