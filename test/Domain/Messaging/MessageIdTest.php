<?php

namespace Novuso\Test\Common\Domain\Messaging;

use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Value\Identifier\Uuid;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Messaging\MessageId
 */
class MessageIdTest extends UnitTestCase
{
    public function test_that_generate_returns_expected_instance()
    {
        $messageId = MessageId::generate();
        $this->assertTrue(Uuid::isValid($messageId->toString()));
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $string = '015d7afe-4533-4332-ba79-4b80f8360a12';
        $messageId = MessageId::fromString($string);
        $this->assertSame($string, $messageId->toString());
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_string()
    {
        $string = '015d7afe-4533-433-ba79-4b80f8360a12';
        MessageId::fromString($string);
    }
}
