<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Mail\Message;

use Novuso\Common\Application\Mail\Message\Attachment;
use Novuso\Common\Application\Mail\Message\MailMessage;
use Novuso\Common\Application\Mail\Message\Priority;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Mail\Message\MailMessage
 */
class MailMessageTest extends UnitTestCase
{
    /** @var MailMessage */
    protected $message;

    protected function setUp(): void
    {
        $this->message = MailMessage::create();
    }

    public function test_that_it_can_set_and_get_subject()
    {
        $subject = 'Test Subject';
        $this->message->setSubject($subject);
        static::assertSame($subject, $this->message->getSubject());
    }

    public function test_that_it_can_set_and_get_from_address()
    {
        $fromAddress = 'from@example.com';
        $fromName = 'From';
        $this->message->addFrom($fromAddress, $fromName);
        $from = $this->message->getFrom();
        static::assertTrue(
            $fromAddress === $from[0]['address']
            && $fromName === $from[0]['name']
        );
    }

    public function test_that_it_can_set_and_get_to_address()
    {
        $toAddress = 'to@example.com';
        $toName = 'To';
        $this->message->addTo($toAddress, $toName);
        $to = $this->message->getTo();
        static::assertTrue(
            $toAddress === $to[0]['address']
            && $toName === $to[0]['name']
        );
    }

    public function test_that_it_can_set_and_get_reply_to_address()
    {
        $replyToAddress = 'replyTo@example.com';
        $replyToName = 'ReplyTo';
        $this->message->addReplyTo($replyToAddress, $replyToName);
        $replyTo = $this->message->getReplyTo();
        static::assertTrue(
            $replyToAddress === $replyTo[0]['address']
            && $replyToName === $replyTo[0]['name']
        );
    }

    public function test_that_it_can_set_and_get_cc_address()
    {
        $ccAddress = 'cc@example.com';
        $ccName = 'CC';
        $this->message->addCc($ccAddress, $ccName);
        $cc = $this->message->getCc();
        static::assertTrue(
            $ccAddress === $cc[0]['address']
            && $ccName === $cc[0]['name']
        );
    }

    public function test_that_it_can_set_and_get_bcc_address()
    {
        $bccAddress = 'bcc@example.com';
        $bccName = 'BCC';
        $this->message->addBcc($bccAddress, $bccName);
        $bcc = $this->message->getBcc();
        static::assertTrue(
            $bccAddress === $bcc[0]['address']
            && $bccName === $bcc[0]['name']
        );
    }

    public function test_that_it_can_set_and_get_content()
    {
        $contentBody = 'Test content';
        $contentType = 'text/plain';
        $this->message->addContent($contentBody, $contentType);
        $content = $this->message->getContent();
        static::assertTrue(
            $contentBody === $content[0]['content']
            && $contentType === $content[0]['content_type']
            && MailMessage::DEFAULT_CHARSET === $content[0]['charset']
        );
    }

    public function test_that_it_can_set_and_get_sender()
    {
        $senderAddress = 'sender@example.com';
        $senderName = 'Sender';
        $this->message->setSender($senderAddress, $senderName);
        $sender = $this->message->getSender();
        static::assertTrue(
            $senderAddress === $sender['address']
            && $senderName === $sender['name']
        );
    }

    public function test_that_it_can_set_and_get_return_path()
    {
        $returnPath = 'return@example.com';
        $this->message->setReturnPath($returnPath);
        static::assertSame($returnPath, $this->message->getReturnPath());
    }

    public function test_that_it_can_set_and_get_charset()
    {
        $charset = 'utf-8';

        $this->message->setCharset($charset);

        static::assertSame($charset, $this->message->getCharset());
    }

    public function test_that_it_can_set_and_get_priority()
    {
        $priority = Priority::HIGH();
        $this->message->setPriority($priority);
        static::assertSame($priority, $this->message->getPriority());
    }

    public function test_that_it_can_set_and_get_timestamp()
    {
        $timestamp = time();
        $this->message->setTimestamp($timestamp);
        static::assertSame($timestamp, $this->message->getTimestamp());
    }

    public function test_that_it_can_set_and_get_max_line_length()
    {
        $maxLineLength = 78;
        $this->message->setMaxLineLength($maxLineLength);
        static::assertSame($maxLineLength, $this->message->getMaxLineLength());
    }

    public function test_that_max_line_length_cannot_exceed_998_characters()
    {
        $this->message->setMaxLineLength(1200);
        static::assertSame(998, $this->message->getMaxLineLength());
    }

    public function test_that_it_can_set_and_get_attachments()
    {
        /** @var Attachment $attachment */
        $attachment = $this->mock(Attachment::class);
        $this->message->addAttachment($attachment);
        $attachments = $this->message->getAttachments();
        static::assertSame($attachment, $attachments[0]);
    }
}
