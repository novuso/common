<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Transport;

use Novuso\Common\Application\Mail\Exception\MailException;
use Novuso\Common\Application\Mail\Message\MailMessage;

/**
 * Interface MailTransport
 */
interface MailTransport
{
    /**
     * Sends a mail message
     *
     * @throws MailException When an error occurs
     */
    public function send(MailMessage $message): void;
}
