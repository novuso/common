<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Message;

/**
 * Class MailMessage
 */
final class MailMessage
{
    /**
     * Default character set
     *
     * @var string
     */
    public const DEFAULT_CHARSET = 'utf-8';

    /**
     * HTML content type
     *
     * @var string
     */
    public const CONTENT_TYPE_HTML = 'text/html';

    /**
     * Plain content type
     *
     * @var string
     */
    public const CONTENT_TYPE_PLAIN = 'text/plain';

    /**
     * Subject
     *
     * @var string|null
     */
    protected $subject;

    /**
     * From addresses
     *
     * @var array
     */
    protected $from = [];

    /**
     * To addresses
     *
     * @var array
     */
    protected $to = [];

    /**
     * Reply-To addresses
     *
     * @var array
     */
    protected $replyTo = [];

    /**
     * CC addresses
     *
     * @var array
     */
    protected $cc = [];

    /**
     * BCC addresses
     *
     * @var array
     */
    protected $bcc = [];

    /**
     * Content parts
     *
     * @var array
     */
    protected $content = [];

    /**
     * Sender
     *
     * @var array|null
     */
    protected $sender;

    /**
     * Return path
     *
     * @var string|null
     */
    protected $returnPath;

    /**
     * Character set
     *
     * @var string
     */
    protected $charset;

    /**
     * Priority
     *
     * @var Priority
     */
    protected $priority;

    /**
     * Unix timestamp
     *
     * @var int|null
     */
    protected $timestamp;

    /**
     * Max line length
     *
     * @var int|null
     */
    protected $maxLineLength;

    /**
     * Attachments
     *
     * @var Attachment[]
     */
    protected $attachments = [];

    /**
     * Constructs MailMessage
     */
    public function __construct()
    {
        $this->setPriority(Priority::NORMAL());
        $this->setCharset(static::DEFAULT_CHARSET);
    }

    /**
     * Creates instance
     *
     * @return MailMessage
     */
    public static function create(): MailMessage
    {
        return new static();
    }

    /**
     * Sets the subject
     *
     * @param string $subject The subject
     *
     * @return MailMessage
     */
    public function setSubject(string $subject): MailMessage
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Retrieves the subject
     *
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * Adds a From address
     *
     * @param string      $address The address
     * @param string|null $name    The name
     *
     * @return MailMessage
     */
    public function addFrom(string $address, ?string $name = null): MailMessage
    {
        $this->from[] = [
            'address' => $address,
            'name'    => $name
        ];

        return $this;
    }

    /**
     * Retrieves From addresses
     *
     * Each address is an array with the following keys:
     *
     * * address => string
     * * name    => string|null
     *
     * @return array
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * Adds a To address
     *
     * @param string      $address The address
     * @param string|null $name    The name
     *
     * @return MailMessage
     */
    public function addTo(string $address, ?string $name = null): MailMessage
    {
        $this->to[] = [
            'address' => $address,
            'name'    => $name
        ];

        return $this;
    }

    /**
     * Retrieves To addresses
     *
     * Each address is an array with the following keys:
     *
     * * address => string
     * * name    => string|null
     *
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * Adds a Reply-To address
     *
     * @param string      $address The address
     * @param string|null $name    The name
     *
     * @return MailMessage
     */
    public function addReplyTo(string $address, ?string $name = null): MailMessage
    {
        $this->replyTo[] = [
            'address' => $address,
            'name'    => $name
        ];

        return $this;
    }

    /**
     * Retrieves Reply-To addresses
     *
     * Each address is an array with the following keys:
     *
     * * address => string
     * * name    => string|null
     *
     * @return array
     */
    public function getReplyTo(): array
    {
        return $this->replyTo;
    }

    /**
     * Adds a CC address
     *
     * @param string      $address The address
     * @param string|null $name    The name
     *
     * @return MailMessage
     */
    public function addCc(string $address, ?string $name = null): MailMessage
    {
        $this->cc[] = [
            'address' => $address,
            'name'    => $name
        ];

        return $this;
    }

    /**
     * Retrieves CC addresses
     *
     * Each address is an array with the following keys:
     *
     * * address => string
     * * name    => string|null
     *
     * @return array
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * Adds a BCC address
     *
     * @param string      $address The address
     * @param string|null $name    The name
     *
     * @return MailMessage
     */
    public function addBcc(string $address, ?string $name = null): MailMessage
    {
        $this->bcc[] = [
            'address' => $address,
            'name'    => $name
        ];

        return $this;
    }

    /**
     * Retrieves BCC addresses
     *
     * Each address is an array with the following keys:
     *
     * * address => string
     * * name    => string|null
     *
     * @return array
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * Adds a content part
     *
     * Example content type: "text/plain" or "text/html"
     *
     * @param string      $content     The content
     * @param string      $contentType The content type
     * @param string|null $charset     The character set
     *
     * @return MailMessage
     */
    public function addContent(string $content, string $contentType, ?string $charset = null): MailMessage
    {
        $charset = $charset ?: $this->getCharset();

        $this->content[] = [
            'content'      => $content,
            'content_type' => $contentType,
            'charset'      => $charset
        ];

        return $this;
    }

    /**
     * Retrieves the content parts
     *
     * Each content part is an array with the following keys:
     *
     * * content      => string
     * * content_type => string
     * * charset      => string
     *
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Sets the sender
     *
     * If set, this has a higher precedence than a from address.
     *
     * @param string      $address The address
     * @param string|null $name    The name
     *
     * @return MailMessage
     */
    public function setSender(string $address, ?string $name = null): MailMessage
    {
        $this->sender = [
            'address' => $address,
            'name'    => $name
        ];

        return $this;
    }

    /**
     * Retrieves the sender
     *
     * The sender is an array with the following keys:
     *
     * * address => string
     * * name    => string|null
     *
     * @return array|null
     */
    public function getSender(): ?array
    {
        return $this->sender;
    }

    /**
     * Sets the return path
     *
     * If set, this determines where bounces should go.
     *
     * @param string $returnPath The email address
     *
     * @return MailMessage
     */
    public function setReturnPath(string $returnPath): MailMessage
    {
        $this->returnPath = $returnPath;

        return $this;
    }

    /**
     * Retrieves the return path
     *
     * @return string|null
     */
    public function getReturnPath(): ?string
    {
        return $this->returnPath;
    }

    /**
     * Sets the character set
     *
     * @param string $charset The character set
     *
     * @return MailMessage
     */
    public function setCharset(string $charset): MailMessage
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Retrieves the character set
     *
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * Sets the priority
     *
     * @param Priority $priority The priority
     *
     * @return MailMessage
     */
    public function setPriority(Priority $priority): MailMessage
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Retrieves the priority
     *
     * @return Priority
     */
    public function getPriority(): Priority
    {
        return $this->priority;
    }

    /**
     * Sets the UNIX timestamp
     *
     * @param int $timestamp The timestamp
     *
     * @return MailMessage
     */
    public function setTimestamp(int $timestamp): MailMessage
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Retrieves the UNIX timestamp
     *
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * Sets the max line length
     *
     * Max line length should be a positive integer less than 998.
     *
     * @param int $maxLineLength The max line length
     *
     * @return MailMessage
     */
    public function setMaxLineLength(int $maxLineLength): MailMessage
    {
        // Each line of characters MUST be no more than 998 characters,
        // and SHOULD be no more than 78 characters, excluding the CRLF.
        // https://tools.ietf.org/html/rfc5322#section-2.1.1
        $maxLineLength = (int) abs($maxLineLength);

        if ($maxLineLength > 998) {
            $maxLineLength = 998;
        }

        $this->maxLineLength = $maxLineLength;

        return $this;
    }

    /**
     * Retrieves the max line length
     *
     * @return int|null
     */
    public function getMaxLineLength(): ?int
    {
        return $this->maxLineLength;
    }

    /**
     * Adds an attachment
     *
     * @param Attachment $attachment The attachment
     *
     * @return MailMessage
     */
    public function addAttachment(Attachment $attachment): MailMessage
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Retrieves the file attachments
     *
     * @return Attachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }
}
