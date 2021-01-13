<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Messaging;

use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Value\Identifier\Uuid;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Messaging\MessageId
 */
class MessageIdTest extends UnitTestCase
{
    public function test_that_generate_returns_expected_instance()
    {
        $messageId = MessageId::generate();

        static::assertTrue(Uuid::isValid($messageId->toString()));
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $string = '015d7afe-4533-4332-ba79-4b80f8360a12';
        $messageId = MessageId::fromString($string);

        static::assertSame($string, $messageId->toString());
    }

    public function test_that_from_string_throws_exception_for_invalid_string()
    {
        $this->expectException(DomainException::class);

        $string = '015d7afe-4533-433-ba79-4b80f8360a12';
        MessageId::fromString($string);
    }
}
