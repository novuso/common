<?php

namespace Novuso\Test\Common\Application\Mail\Message;

use Novuso\Common\Application\Mail\Message\Attachment;
use Novuso\Common\Application\Mail\Message\Message;
use Novuso\Common\Application\Mail\Message\Priority;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Mail\Message\Message
 */
class MessageTest extends UnitTestCase
{
    /**
     * @var Message
     */
    protected $message;

    protected function setUp()
    {
        $this->message = Message::create();
    }

    public function test_that_it_can_set_and_get_subject()
    {
        $subject = 'Test Subject';
        $this->message->setSubject($subject);
        $this->assertSame($subject, $this->message->getSubject());
    }

    public function test_that_it_can_set_and_get_from_address()
    {
        $fromAddress = 'from@example.com';
        $fromName = 'From';
        $this->message->addFrom($fromAddress, $fromName);
        $from = $this->message->getFrom();
        $this->assertTrue(
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
        $this->assertTrue(
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
        $this->assertTrue(
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
        $this->assertTrue(
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
        $this->assertTrue(
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
        $this->assertTrue(
            $contentBody === $content[0]['content']
            && $contentType === $content[0]['content_type']
            && Message::DEFAULT_CHARSET === $content[0]['charset']
        );
    }

    public function test_that_it_can_set_and_get_sender()
    {
        $senderAddress = 'sender@example.com';
        $senderName = 'Sender';
        $this->message->setSender($senderAddress, $senderName);
        $sender = $this->message->getSender();
        $this->assertTrue(
            $senderAddress === $sender['address']
            && $senderName === $sender['name']
        );
    }

    public function test_that_it_can_set_and_get_return_path()
    {
        $returnPath = 'return@example.com';
        $this->message->setReturnPath($returnPath);
        $this->assertSame($returnPath, $this->message->getReturnPath());
    }

    public function test_that_it_can_set_and_get_priority()
    {
        $priority = Priority::HIGH();
        $this->message->setPriority($priority);
        $this->assertSame($priority, $this->message->getPriority());
    }

    public function test_that_it_can_set_and_get_timestamp()
    {
        $timestamp = time();
        $this->message->setTimestamp($timestamp);
        $this->assertSame($timestamp, $this->message->getTimestamp());
    }

    public function test_that_it_can_set_and_get_max_line_length()
    {
        $maxLineLength = 78;
        $this->message->setMaxLineLength($maxLineLength);
        $this->assertSame($maxLineLength, $this->message->getMaxLineLength());
    }

    public function test_that_max_line_length_cannot_exceed_998_characters()
    {
        $this->message->setMaxLineLength(1200);
        $this->assertSame(998, $this->message->getMaxLineLength());
    }

    public function test_that_it_can_set_and_get_attachments()
    {
        /** @var Attachment $attachment */
        $attachment = $this->mock(Attachment::class);
        $this->message->addAttachment($attachment);
        $attachments = $this->message->getAttachments();
        $this->assertSame($attachment, $attachments[0]);
    }
}
