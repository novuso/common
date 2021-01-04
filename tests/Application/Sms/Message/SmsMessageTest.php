<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Sms\Message;

use Novuso\Common\Application\Sms\Message\SmsMessage;
use Novuso\Common\Domain\Value\Identifier\Url;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Sms\Message\SmsMessage
 */
class SmsMessageTest extends UnitTestCase
{
    /** @var SmsMessage */
    protected $message;
    /** @var string */
    protected $to;
    /** @var string */
    protected $from;

    protected function setUp(): void
    {
        $this->to = '+12105551212';
        $this->from = '+12105551111';
        $this->message = SmsMessage::create($this->to, $this->from);
    }

    public function test_that_get_to_returns_expected_value()
    {
        static::assertSame($this->to, $this->message->getTo());
    }

    public function test_that_get_from_returns_expected_value()
    {
        static::assertSame($this->from, $this->message->getFrom());
    }

    public function test_that_get_body_returns_null_by_default()
    {
        static::assertNull($this->message->getBody());
    }

    public function test_that_set_and_get_body_work_as_expected()
    {
        $body = 'This is an SMS message';
        $this->message->setBody($body);
        static::assertSame($body, $this->message->getBody());
    }

    public function test_that_add_and_get_media_work_as_expected()
    {
        $mediaUrls = [
            'https://http.cat/200',
            'https://http.cat/404'
        ];

        foreach ($mediaUrls as $mediaUrl) {
            $this->message->addMedia(Url::parse($mediaUrl));
        }

        $urls = [];
        foreach ($this->message->getMedia() as $url) {
            $urls[] = $url->toString();
        }

        static::assertSame($mediaUrls, $urls);
    }
}
