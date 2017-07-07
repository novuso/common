<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Command;

use Novuso\Common\Application\Mail\Message;
use Novuso\Common\Application\Mail\Priority;
use Novuso\Common\Domain\Messaging\Command\Command;

/**
 * SendMailCommand is a command to send a simple mail message
 *
 * Attachments are not supported by this command
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SendMailCommand implements Command
{
    /**
     * Mail message
     *
     * @var Message
     */
    protected $message;

    /**
     * Constructs SendMailCommand
     *
     * @param Message $message The message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Retrieves the message
     *
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data): SendMailCommand
    {
        $message = Message::create();

        if (isset($data['subject'])) {
            $message->setSubject($data['subject']);
        }

        if (isset($data['from']) && is_array($data['from'])) {
            foreach ($data['from'] as $item) {
                $message->addFrom($item['address'], $item['name']);
            }
        }

        if (isset($data['to']) && is_array($data['to'])) {
            foreach ($data['to'] as $item) {
                $message->addTo($item['address'], $item['name']);
            }
        }

        if (isset($data['reply_to']) && is_array($data['reply_to'])) {
            foreach ($data['reply_to'] as $item) {
                $message->addReplyTo($item['address'], $item['name']);
            }
        }

        if (isset($data['cc']) && is_array($data['cc'])) {
            foreach ($data['cc'] as $item) {
                $message->addCc($item['address'], $item['name']);
            }
        }

        if (isset($data['bcc']) && is_array($data['bcc'])) {
            foreach ($data['bcc'] as $item) {
                $message->addBcc($item['address'], $item['name']);
            }
        }

        if (isset($data['content']) && is_array($data['content'])) {
            foreach ($data['content'] as $item) {
                $content = base64_decode($item['content']);
                $contentType = $item['content_type'];
                $charset = $item['charset'];
                $message->addContent($content, $contentType, $charset);
            }
        }

        if (isset($data['sender']) && is_array($data['sender'])) {
            $message->setSender($data['sender']['address'], $data['sender']['name']);
        }

        if (isset($data['return_path'])) {
            $message->setReturnPath($data['return_path']);
        }

        if (isset($data['charset'])) {
            $message->setCharset($data['charset']);
        }

        if (isset($data['priority'])) {
            /** @var Priority $priority */
            $priority = Priority::fromName($data['priority']);
            $message->setPriority($priority);
        }

        if (isset($data['timestamp'])) {
            $message->setTimestamp($data['timestamp']);
        }

        if (isset($data['max_line_length'])) {
            $message->setMaxLineLength($data['max_line_length']);
        }

        return new static($message);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $data = [
            'subject'         => $this->message->getSubject(),
            'from'            => $this->message->getFrom(),
            'to'              => $this->message->getTo(),
            'reply_to'        => $this->message->getReplyTo(),
            'cc'              => $this->message->getCc(),
            'bcc'             => $this->message->getBcc(),
            'sender'          => $this->message->getSender(),
            'return_path'     => $this->message->getReturnPath(),
            'charset'         => $this->message->getCharset(),
            'priority'        => $this->message->getPriority()->name(),
            'timestamp'       => $this->message->getTimestamp(),
            'max_line_length' => $this->message->getMaxLineLength()
        ];

        $content = [];
        $contentParts = $this->message->getContent();
        foreach ($contentParts as $part) {
            $content[] = [
                'content'      => base64_encode($part['content']),
                'content_type' => $part['content_type'],
                'charset'      => $part['charset']
            ];
        }
        $data['content'] = $content;

        return $data;
    }
}
