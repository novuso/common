<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Sms;

use Mockery\MockInterface;
use Novuso\Common\Application\Sms\Message\SmsMessage;
use Novuso\Common\Application\Sms\SmsService;
use Novuso\Common\Application\Sms\Transport\SmsTransport;
use Novuso\Common\Domain\Value\Identifier\Url;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Sms\SmsService
 */
class SmsServiceTest extends UnitTestCase
{
    /** @var SmsService */
    protected $smsService;
    /** @var SmsTransport|MockInterface */
    protected $mockSmsTransport;

    protected function setUp(): void
    {
        $this->mockSmsTransport = $this->mock(SmsTransport::class);
        $this->smsService = new SmsService($this->mockSmsTransport);
    }

    public function test_that_send_delegates_to_sms_transport()
    {
        $message = SmsMessage::create('+12105551212', '+12105551111');

        $this->mockSmsTransport
            ->shouldReceive('send')
            ->once()
            ->with($message)
            ->andReturnNull();

        $this->smsService->send($message);
    }

    public function test_that_create_message_returns_expected_message_to_and_from()
    {
        $to = '+12105551212';
        $from = '+12105551111';

        $message = $this->smsService->createMessage($to, $from);

        $this->assertTrue(
            $to === $message->getTo()
            && $from === $message->getFrom()
        );
    }

    public function test_that_create_message_returns_expected_message_with_body()
    {
        $to = '+12105551212';
        $from = '+12105551111';
        $body = 'SMS Message';

        $message = $this->smsService->createMessage($to, $from, $body);

        $this->assertTrue(
            $to === $message->getTo()
            && $from === $message->getFrom()
            && $body === $message->getBody()
        );
    }

    public function test_that_create_message_returns_expected_message_with_media_urls()
    {
        $to = '+12105551212';
        $from = '+12105551111';
        $body = null;
        $mediaUrls = [
            'https://http.cat/200',
            'https://http.cat/404'
        ];

        $message = $this->smsService->createMessage($to, $from, $body, $mediaUrls);

        $this->assertTrue(
            $to === $message->getTo()
            && $from === $message->getFrom()
            && null === $message->getBody()
            && Url::parse($mediaUrls[0])->equals($message->getMedia()[0])
            && Url::parse($mediaUrls[1])->equals($message->getMedia()[1])
        );
    }
}
