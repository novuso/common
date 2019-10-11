<?php declare(strict_types=1);

namespace Novuso\Common\Application\Sms\Message;

use Novuso\Common\Domain\Value\Identifier\Url;

/**
 * Class SmsMessage
 */
final class SmsMessage
{
    /**
     * To phone number
     *
     * @var string
     */
    protected $to;

    /**
     * From phone number
     *
     * @var string
     */
    protected $from;

    /**
     * Message body
     *
     * @var string|null
     */
    protected $body;

    /**
     * Media URLs
     *
     * @var Url[]
     */
    protected $media = [];

    /**
     * Constructs SmsMessage
     *
     * @param string $to   The To phone number
     * @param string $from The From phone number
     */
    public function __construct(string $to, string $from)
    {
        $this->to = $to;
        $this->from = $from;
    }

    /**
     * Creates instance
     *
     * @param string $to   The To phone number
     * @param string $from The From phone number
     *
     * @return SmsMessage
     */
    public static function create(string $to, string $from): SmsMessage
    {
        return new static($to, $from);
    }

    /**
     * Retrieves the To phone number
     *
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * Retrieves the From phone number
     *
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * Sets the message body
     *
     * @param string $body The message body
     *
     * @return SmsMessage
     */
    public function setBody(string $body): SmsMessage
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Retrieves the message body
     *
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Adds a media URL
     *
     * @param Url $url The media URL
     *
     * @return SmsMessage
     */
    public function addMedia(Url $url): SmsMessage
    {
        $this->media[] = $url;

        return $this;
    }

    /**
     * Retrieves the media URLs
     *
     * @return Url[]
     */
    public function getMedia(): array
    {
        return $this->media;
    }
}
