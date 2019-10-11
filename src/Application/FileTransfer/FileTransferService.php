<?php declare(strict_types=1);

namespace Novuso\Common\Application\FileTransfer;

use Novuso\Common\Application\FileTransfer\Exception\FileTransferException;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;

/**
 * Class FileTransferService
 */
final class FileTransferService
{
    /**
     * Transport table
     *
     * @var HashTable
     */
    protected $transports;

    /**
     * Constructs FileTransferService
     */
    public function __construct()
    {
        $this->transports = HashTable::of('string', FileTransport::class);
    }

    /**
     * @param string $key
     *
     * @return FileTransport
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
     * @param string        $key       The transport key
     * @param FileTransport $transport The file transport
     *
     * @return void
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
