<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Sms\Message;

use Novuso\Common\Domain\Value\Identifier\Url;

/**
 * Class SmsMessage
 */
final class SmsMessage
{
    protected ?string $body = null;
    protected array $media = [];

    /**
     * Constructs SmsMessage
     */
    public function __construct(protected string $to, protected string $from)
    {
    }

    /**
     * Creates instance
     */
    public static function create(string $to, string $from): SmsMessage
    {
        return new static($to, $from);
    }

    /**
     * Retrieves the To phone number
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * Retrieves the From phone number
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * Sets the message body
     */
    public function setBody(string $body): SmsMessage
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Retrieves the message body
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Adds a media URL
     */
    public function addMedia(Url $url): SmsMessage
    {
        $this->media[] = $url;

        return $this;
    }

    /**
     * Retrieves the media URLs
     */
    public function getMedia(): array
    {
        return $this->media;
    }
}
