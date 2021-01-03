<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Socket;

use Novuso\Common\Application\Socket\Exception\SocketException;

/**
 * Interface Publisher
 */
interface Publisher
{
    /**
     * Pushes a socket message
     *
     * @throws SocketException When an error occurs
     */
    public function push(string $topic, string $message): void;
}
