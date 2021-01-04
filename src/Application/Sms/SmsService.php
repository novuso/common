<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Sms;

use Novuso\Common\Application\Sms\Message\SmsFactory;
use Novuso\Common\Application\Sms\Message\SmsMessage;
use Novuso\Common\Application\Sms\Transport\SmsTransport;
use Novuso\Common\Domain\Value\Identifier\Url;

/**
 * Class SmsService
 */
final class SmsService implements SmsTransport, SmsFactory
{
    /**
     * Constructs SmsService
     */
    public function __construct(protected SmsTransport $transport)
    {
    }

    /**
     * @inheritDoc
     */
    public function send(SmsMessage $message): void
    {
        $this->transport->send($message);
    }

    /**
     * @inheritDoc
     */
    public function createMessage(
        string $to,
        string $from,
        ?string $body = null,
        array $mediaUrls = []
    ): SmsMessage {
        $message = SmsMessage::create($to, $from);

        if ($body !== null) {
            $message->setBody($body);
        }

        foreach ($mediaUrls as $url) {
            if ($url instanceof Url) {
                $message->addMedia($url);
            } else {
                $message->addMedia($this->createMediaUrl($url));
            }
        }

        return $message;
    }

    /**
     * @inheritDoc
     */
    public function createMediaUrl(string $url): Url
    {
        return Url::parse($url);
    }
}
