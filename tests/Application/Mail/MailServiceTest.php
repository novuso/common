<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Mail;

use Mockery\MockInterface;
use Novuso\Common\Application\Mail\MailService;
use Novuso\Common\Application\Mail\Message\Attachment;
use Novuso\Common\Application\Mail\Message\MailFactory;
use Novuso\Common\Application\Mail\Message\MailMessage;
use Novuso\Common\Application\Mail\Transport\MailTransport;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Mail\MailService
 */
class MailServiceTest extends UnitTestCase
{
    /** @var MailService */
    protected $mailService;
    /** @var MailTransport|MockInterface */
    protected $mockMailTransport;
    /** @var MailFactory|MockInterface */
    protected $mockMailFactory;

    protected function setUp(): void
    {
        $this->mockMailTransport = $this->mock(MailTransport::class);
        $this->mockMailFactory = $this->mock(MailFactory::class);
        $this->mailService = new MailService(
            $this->mockMailTransport,
            $this->mockMailFactory
        );
    }

    public function test_that_send_delegates_to_mail_transport()
    {
        $message = MailMessage::create();

        $this->mockMailTransport
            ->shouldReceive('send')
            ->once()
            ->with($message)
            ->andReturnNull();

        $this->mailService->send($message);
    }

    public function test_that_create_message_delegates_to_mail_factory()
    {
        $message = MailMessage::create();

        $this->mockMailFactory
            ->shouldReceive('createMessage')
            ->once()
            ->andReturn($message);

        $this->assertSame($message, $this->mailService->createMessage());
    }

    public function test_that_create_attachment_from_string_delegates_to_mail_factory()
    {
        $attachment = $this->mock(Attachment::class);

        $body = 'Test content';
        $fileName = 'test.txt';
        $contentType = 'application/octet-stream';
        $embedId = null;

        $this->mockMailFactory
            ->shouldReceive('createAttachmentFromString')
            ->once()
            ->with($body, $fileName, $contentType, $embedId)
            ->andReturn($attachment);

        $this->assertSame(
            $attachment,
            $this->mailService->createAttachmentFromString($body, $fileName, $contentType, $embedId)
        );
    }

    public function test_that_create_attachment_from_path_delegates_to_mail_factory()
    {
        $attachment = $this->mock(Attachment::class);

        $path = '/tmp/test.txt';
        $fileName = 'test.txt';
        $contentType = 'application/octet-stream';
        $embedId = null;

        $this->mockMailFactory
            ->shouldReceive('createAttachmentFromPath')
            ->once()
            ->with($path, $fileName, $contentType, $embedId)
            ->andReturn($attachment);

        $this->assertSame(
            $attachment,
            $this->mailService->createAttachmentFromPath($path, $fileName, $contentType, $embedId)
        );
    }

    public function test_that_generate_embed_id_delegates_to_mail_factory()
    {
        $id = '123';

        $this->mockMailFactory
            ->shouldReceive('generateEmbedId')
            ->once()
            ->andReturn($id);

        $this->assertSame($id, $this->mailService->generateEmbedId());
    }
}
