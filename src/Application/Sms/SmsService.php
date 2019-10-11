<?php declare(strict_types=1);

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
     * SMS transport
     *
     * @var SmsTransport
     */
    protected $transport;

    /**
     * Constructs SmsService
     *
     * @param SmsTransport $transport The SMS transport
     */
    public function __construct(SmsTransport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * {@inheritdoc}
     */
    public function send(SmsMessage $message): void
    {
        $this->transport->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage(string $to, string $from, ?string $body = null, array $mediaUrls = []): SmsMessage
    {
        $message = SmsMessage::create($to, $from);

        if ($body !== null) {
            $message->setBody($body);
        }

        foreach ($mediaUrls as $url) {
            $message->addMedia($this->createMediaUrl($url));
        }

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function createMediaUrl(string $url): Url
    {
        /** @var Url $media */
        $media = Url::parse($url);

        return $media;
    }
}
