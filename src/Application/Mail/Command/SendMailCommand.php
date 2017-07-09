<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Command;

use Novuso\Common\Application\Mail\Message;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\System\Exception\DomainException;
use function Novuso\Common\Functions\var_print;

/**
 * SendMailCommand is a command to send an email message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SendMailCommand implements Command
{
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
    protected $from;

    /**
     * To addresses
     *
     * @var array
     */
    protected $to;

    /**
     * Reply-To addresses
     *
     * @var array
     */
    protected $replyTo;

    /**
     * CC addresses
     *
     * @var array
     */
    protected $cc;

    /**
     * BCC addresses
     *
     * @var array
     */
    protected $bcc;

    /**
     * Content parts
     *
     * @var array
     */
    protected $content;

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
     * @var string|null
     */
    protected $charset;

    /**
     * Priority
     *
     * @var string|null
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
     * @var array
     */
    protected $attachments;

    /**
     * Message keys
     *
     * @var array
     */
    protected static $keys = [
        'subject',
        'from',
        'to',
        'reply_to',
        'cc',
        'bcc',
        'content',
        'sender',
        'return_path',
        'charset',
        'priority',
        'timestamp',
        'max_line_length',
        'attachments'
    ];

    /**
     * Constructs SendMailCommand
     *
     * @param null|string $subject       The subject
     * @param array       $from          The FROM addresses
     * @param array       $to            The TO addresses
     * @param array       $replyTo       The REPLY-TO addresses
     * @param array       $cc            The CC addresses
     * @param array       $bcc           The BCC addresses
     * @param array       $content       The content parts
     * @param array|null  $sender        The sender address
     * @param string|null $returnPath    The return path
     * @param string|null $charset       The character set
     * @param string|null $priority      The priority
     * @param int|null    $timestamp     The timestamp
     * @param int|null    $maxLineLength The maximum line length
     * @param array       $attachments   The attachments
     */
    public function __construct(
        ?string $subject = null,
        array $from = [],
        array $to = [],
        array $replyTo = [],
        array $cc = [],
        array $bcc = [],
        array $content = [],
        ?array $sender = null,
        ?string $returnPath = null,
        ?string $charset = null,
        ?string $priority = null,
        ?int $timestamp = null,
        ?int $maxLineLength = null,
        array $attachments = []
    ) {
        $this->subject = $subject;
        $this->from = $from;
        $this->to = $to;
        $this->replyTo = $replyTo;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->content = $content;
        $this->sender = $sender;
        $this->returnPath = $returnPath;
        $this->charset = $charset;
        $this->priority = $priority;
        $this->timestamp = $timestamp;
        $this->maxLineLength = $maxLineLength;
        $this->attachments = $attachments;
    }

    /**
     * Creates instance from a Mail Message
     *
     * @param Message $message The mail message
     *
     * @return SendMailCommand
     */
    public static function fromMessage(Message $message): SendMailCommand
    {
        $subject = $message->getSubject();
        $from = $message->getFrom();
        $to = $message->getTo();
        $replyTo = $message->getReplyTo();
        $cc = $message->getCc();
        $bcc = $message->getBcc();
        $content = [];
        $sender = $message->getSender();
        $returnPath = $message->getReturnPath();
        $charset = $message->getCharset();
        $priority = $message->getPriority()->name();
        $timestamp = $message->getTimestamp();
        $maxLineLength = $message->getMaxLineLength();
        $attachments = [];

        foreach ($message->getContent() as $contentPart) {
            $content[] = [
                'content'      => base64_encode($contentPart['content']),
                'content_type' => $contentPart['content_type'],
                'charset'      => $contentPart['charset']
            ];
        }

        foreach ($message->getAttachments() as $attachment) {
            $attachments[] = [
                'id'           => $attachment->getId(),
                'body'         => base64_encode($attachment->getBody()),
                'file_name'    => $attachment->getFileName(),
                'content_type' => $attachment->getContentType(),
                'disposition'  => $attachment->getDisposition()
            ];
        }

        return new static(
            $subject,
            $from,
            $to,
            $replyTo,
            $cc,
            $bcc,
            $content,
            $sender,
            $returnPath,
            $charset,
            $priority,
            $timestamp,
            $maxLineLength,
            $attachments
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data): SendMailCommand
    {
        foreach (static::$keys as $key) {
            if (!array_key_exists($key, $data)) {
                $message = sprintf(
                    'invalid format, missing %s key: %s',
                    $key,
                    var_print($data)
                );
                throw new DomainException($message);
            }
        }

        return new static(
            $data['subject'],
            $data['from'],
            $data['to'],
            $data['reply_to'],
            $data['cc'],
            $data['bcc'],
            $data['content'],
            $data['sender'],
            $data['return_path'],
            $data['charset'],
            $data['priority'],
            $data['timestamp'],
            $data['max_line_length'],
            $data['attachments']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'subject'         => $this->subject(),
            'from'            => $this->from(),
            'to'              => $this->to(),
            'reply_to'        => $this->replyTo(),
            'cc'              => $this->cc(),
            'bcc'             => $this->bcc(),
            'content'         => $this->content(),
            'sender'          => $this->sender(),
            'return_path'     => $this->returnPath(),
            'charset'         => $this->charset(),
            'priority'        => $this->priority(),
            'timestamp'       => $this->timestamp(),
            'max_line_length' => $this->maxLineLength(),
            'attachments'     => $this->attachments()
        ];
    }

    /**
     * Retrieves the subject
     *
     * @return string|null
     */
    public function subject(): ?string
    {
        return $this->subject;
    }

    /**
     * Retrieves From addresses
     *
     * @return array
     */
    public function from(): array
    {
        return $this->from;
    }

    /**
     * Retrieves To addresses
     *
     * @return array
     */
    public function to(): array
    {
        return $this->to;
    }

    /**
     * Retrieves Reply-To addresses
     *
     * @return array
     */
    public function replyTo(): array
    {
        return $this->replyTo;
    }

    /**
     * Retrieves CC addresses
     *
     * @return array
     */
    public function cc(): array
    {
        return $this->cc;
    }

    /**
     * Retrieves BCC addresses
     *
     * @return array
     */
    public function bcc(): array
    {
        return $this->bcc;
    }

    /**
     * Retrieves content-parts
     *
     * @return array
     */
    public function content(): array
    {
        return $this->content;
    }

    /**
     * Retrieves sender
     *
     * @return array|null
     */
    public function sender(): ?array
    {
        return $this->sender;
    }

    /**
     * Retrieves return path
     *
     * @return string|null
     */
    public function returnPath(): ?string
    {
        return $this->returnPath;
    }

    /**
     * Retrieves the character set
     *
     * @return string|null
     */
    public function charset(): ?string
    {
        return $this->charset;
    }

    /**
     * Retrieves the priority
     *
     * @return string|null
     */
    public function priority(): ?string
    {
        return $this->priority;
    }

    /**
     * Retrieves the timestamp
     *
     * @return int|null
     */
    public function timestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * Retrieves the max line length
     *
     * @return int|null
     */
    public function maxLineLength(): ?int
    {
        return $this->maxLineLength;
    }

    /**
     * Retrieves the attachments
     *
     * @return array
     */
    public function attachments(): array
    {
        return $this->attachments;
    }
}
