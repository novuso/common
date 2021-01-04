<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\Common\Test\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Test\TestCase\UnitTestCase;
use Novuso\System\Utility\ClassName;

/**
 * @covers \Novuso\Common\Domain\Messaging\Command\CommandMessage
 * @covers \Novuso\Common\Domain\Messaging\BaseMessage
 */
class CommandMessageTest extends UnitTestCase
{
    /** @var CommandMessage */
    protected $message;

    protected function setUp(): void
    {
        $this->message = new CommandMessage(
            MessageId::fromString('0150deae-68df-40ca-aea1-6b4b06aadfc3'),
            DateTime::fromString('2015-11-06T15:23:03.000000[America/Chicago]'),
            RegisterUserCommand::fromArray([
                'prefix'      => null,
                'first_name'  => 'James',
                'middle_name' => 'D',
                'last_name'   => 'Smith',
                'suffix'      => null,
                'email'       => 'jsmith@example.com',
                'password'    => '$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC'
            ]),
            MetaData::create(['foo' => 'bar'])
        );
    }

    public function test_that_create_returns_message_from_command()
    {
        $command = new RegisterUserCommand();
        $command
            ->setFirstName('James')
            ->setMiddleName('D')
            ->setLastName('Smith')
            ->setEmail('jsmith@example.com')
            ->setPassword('secret');
        $message = CommandMessage::create($command);
        /** @var RegisterUserCommand $payload */
        $payload = $message->payload();
        static::assertSame('jsmith@example.com', $payload->getEmail());
    }

    public function test_that_id_returns_expected_instance()
    {
        static::assertSame('0150deae-68df-40ca-aea1-6b4b06aadfc3', (string) $this->message->id());
    }

    public function test_that_type_returns_expected_instance()
    {
        static::assertSame('command', $this->message->type()->value());
    }

    public function test_that_timestamp_returns_expected_instance()
    {
        static::assertSame('2015-11-06T15:23:03.000000[America/Chicago]', (string) $this->message->timestamp());
    }

    public function test_that_payload_returns_expected_instance()
    {
        $data = [
            'prefix'      => null,
            'first_name'  => 'James',
            'middle_name' => 'D',
            'last_name'   => 'Smith',
            'suffix'      => null,
            'email'       => 'jsmith@example.com',
            'password'    => '$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC'
        ];
        static::assertSame($data, $this->message->payload()->toArray());
    }

    public function test_that_payload_type_returns_expected_instance()
    {
        $expected = 'Novuso.Common.Test.Resources.Domain.Messaging.Command.RegisterUserCommand';
        static::assertSame($expected, (string) $this->message->payloadType());
    }

    public function test_that_meta_data_returns_expected_instance()
    {
        static::assertSame('{"foo":"bar"}', (string) $this->message->metaData());
    }

    public function test_that_with_meta_data_replaces_meta_data()
    {
        $metaData = MetaData::create(['ip_address' => '127.0.0.1']);
        $this->message = $this->message->withMetaData($metaData);
        static::assertSame('{"ip_address":"127.0.0.1"}', (string) $this->message->metaData());
    }

    public function test_that_merge_meta_data_merges_meta_data()
    {
        $metaData = MetaData::create(['ip_address' => '127.0.0.1']);
        $this->message = $this->message->mergeMetaData($metaData);
        static::assertSame('{"foo":"bar","ip_address":"127.0.0.1"}', (string) $this->message->metaData());
    }

    public function test_that_to_string_returns_expected_value()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"command",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Command.RegisterUserCommand",'
            .'"payload":{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith",'
            .'"suffix":null,"email":"jsmith@example.com",'
            .'"password":"$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC"},'
            .'"meta_data":{"foo":"bar"}}';
        static::assertSame($expected, $this->message->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"command",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Command.RegisterUserCommand",'
            .'"payload":{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith",'
            .'"suffix":null,"email":"jsmith@example.com",'
            .'"password":"$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC"},'
            .'"meta_data":{"foo":"bar"}}';
        static::assertSame($expected, (string) $this->message);
    }

    public function test_that_it_is_json_encodable()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"command",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Command.RegisterUserCommand",'
            .'"payload":{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith",'
            .'"suffix":null,"email":"jsmith@example.com",'
            .'"password":"$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC"},'
            .'"meta_data":{"foo":"bar"}}';
        static::assertSame($expected, json_encode($this->message, JSON_UNESCAPED_SLASHES));
    }

    public function test_that_it_is_serializable()
    {
        $state = serialize($this->message);
        /** @var CommandMessage $message */
        $message = unserialize($state);
        static::assertTrue($message->equals($this->message));
    }

    public function test_that_it_is_system_serializable()
    {
        $serializer = new JsonSerializer();
        $state = $serializer->serialize($this->message);
        /** @var CommandMessage $message */
        $message = $serializer->deserialize($state);
        static::assertTrue($message->equals($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        static::assertSame(0, $this->message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $message = CommandMessage::arrayDeserialize($this->getMessageData());
        static::assertSame(0, $this->message->compareTo($message));
    }

    public function test_that_compare_to_returns_one_for_greater_instance()
    {
        $message = CommandMessage::arrayDeserialize($this->getAltMessageData());
        static::assertSame(1, $message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_instance()
    {
        $message = CommandMessage::arrayDeserialize($this->getAltMessageData());
        static::assertSame(-1, $this->message->compareTo($message));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        static::assertTrue($this->message->equals($this->message));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $message = CommandMessage::arrayDeserialize($this->getMessageData());
        static::assertTrue($this->message->equals($message));
    }

    public function test_that_equals_returns_false_for_invalid_argument()
    {
        static::assertFalse($this->message->equals(null));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $message = CommandMessage::arrayDeserialize($this->getAltMessageData());
        static::assertFalse($this->message->equals($message));
    }

    public function test_that_hash_value_returns_expected_value()
    {
        static::assertSame(
            sprintf(
                '%s:%s:0150deae68df40caaea16b4b06aadfc3',
                ClassName::short(CommandMessage::class),
                ClassName::canonical(MessageId::class)
            ),
            $this->message->hashValue()
        );
    }

    public function test_that_deserialize_throws_exception_for_invalid_data()
    {
        $this->expectException(DomainException::class);

        CommandMessage::arrayDeserialize([]);
    }

    public function test_that_deserialize_throws_exception_for_invalid_type()
    {
        $this->expectException(DomainException::class);

        $data = $this->getMessageData();
        $data['type'] = 'event';
        CommandMessage::arrayDeserialize($data);
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        $this->message->compareTo(null);
    }

    protected function getMessageData()
    {
        return [
            'id'           => '0150deae-68df-40ca-aea1-6b4b06aadfc3',
            'type'         => 'command',
            'timestamp'    => '2015-11-06T15:23:03.000000[America/Chicago]',
            'payload_type' => 'Novuso.Common.Test.Resources.Domain.Messaging.Command.RegisterUserCommand',
            'payload'      => [
                'prefix'      => null,
                'first_name'  => 'James',
                'middle_name' => 'D',
                'last_name'   => 'Smith',
                'suffix'      => null,
                'email'       => 'jsmith@example.com',
                'password'    => '$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC'
            ],
            'meta_data'    => ['foo' => 'bar']
        ];
    }

    protected function getAltMessageData()
    {
        return [
            'id'           => '015d7af5-4e30-4ec6-afa8-6be1114f5b69',
            'type'         => 'command',
            'timestamp'    => '2017-07-25T18:14:42.000000[America/Chicago]',
            'payload_type' => 'Novuso.Common.Test.Resources.Domain.Messaging.Command.RegisterUserCommand',
            'payload'      => [
                'prefix'      => null,
                'first_name'  => 'James',
                'middle_name' => 'D',
                'last_name'   => 'Smith',
                'suffix'      => null,
                'email'       => 'jsmith@example.com',
                'password'    => '$2y$10$kGo6Qs37vLJqhtIJ2sb0YudwHDQTvG77ZE.AMxxp8Fu.4uXYAu/q2'
            ],
            'meta_data'    => ['foo' => 'bar']
        ];
    }
}
