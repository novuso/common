<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Transport;

use Novuso\Common\Application\Mail\Exception\MailException;
use Novuso\Common\Application\Mail\Message\Message;

/**
 * Mailer is the interface for the mail sender
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Mailer
{
    /**
     * Sends a message
     *
     * @param Message $message The message
     *
     * @return void
     *
     * @throws MailException When an error occurs
     */
    public function send(Message $message): void;
}
