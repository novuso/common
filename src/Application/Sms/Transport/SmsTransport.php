<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Sms\Transport;

use Novuso\Common\Application\Sms\Exception\SmsException;
use Novuso\Common\Application\Sms\Message\SmsMessage;

/**
 * Interface SmsTransport
 */
interface SmsTransport
{
    /**
     * Sends an SMS message
     *
     * @param SmsMessage $message The SMS message
     *
     * @return void
     *
     * @throws SmsException When an error occurs
     */
    public function send(SmsMessage $message): void;
}
