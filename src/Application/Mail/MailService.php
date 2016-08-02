<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail;

use Novuso\Common\Application\Mail\Exception\MailException;

/**
 * MailService is the interface for the mail service
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface MailService
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
    public function send(Message $message);
}
