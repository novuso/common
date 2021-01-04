<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\Common\Test\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Test\TestCase\UnitTestCase;
use Novuso\System\Utility\ClassName;

/**
 * @covers \Novuso\Common\Domain\Messaging\Event\EventMessage
 */
class EventMessageTest extends UnitTestCase
{
    /** @var EventMessage */
    protected $message;

    protected function setUp(): void
    {
        $this->message = new EventMessage(
            MessageId::fromString('0150deae-68df-40ca-aea1-6b4b06aadfc3'),
            DateTime::fromString('2015-11-06T15:23:03.000000[America/Chicago]'),
            UserRegisteredEvent::fromArray([
                'prefix'      => null,
                'first_name'  => 'James',
                'middle_name' => 'D',
                'last_name'   => 'Smith',
                'suffix'      => null,
                'email'       => 'jsmith@example.com'
            ]),
            MetaData::create(['foo' => 'bar'])
        );
    }

    public function test_that_create_returns_message_from_event()
    {
        $data = $this->getMessageData();
        $event = UserRegisteredEvent::fromArray($data['payload']);
        $message = EventMessage::create($event);
        /** @var UserRegisteredEvent $payload */
        $payload = $message->payload();
        static::assertSame('jsmith@example.com', $payload->email());
    }

    public function test_that_id_returns_expected_instance()
    {
        static::assertSame('0150deae-68df-40ca-aea1-6b4b06aadfc3', (string) $this->message->id());
    }

    public function test_that_type_returns_expected_instance()
    {
        static::assertSame('event', $this->message->type()->value());
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
            'email'       => 'jsmith@example.com'
        ];
        static::assertSame($data, $this->message->payload()->toArray());
    }

    public function test_that_payload_type_returns_expected_instance()
    {
        $expected = 'Novuso.Common.Test.Resources.Domain.Messaging.Event.UserRegisteredEvent';
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
            .'"type":"event",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Event.UserRegisteredEvent",'
            .'"payload":{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith",'
            .'"suffix":null,"email":"jsmith@example.com"},'
            .'"meta_data":{"foo":"bar"}}';
        static::assertSame($expected, $this->message->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"event",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Event.UserRegisteredEvent",'
            .'"payload":{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith",'
            .'"suffix":null,"email":"jsmith@example.com"},'
            .'"meta_data":{"foo":"bar"}}';
        static::assertSame($expected, (string) $this->message);
    }

    public function test_that_it_is_json_encodable()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"event",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Event.UserRegisteredEvent",'
            .'"payload":{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith",'
            .'"suffix":null,"email":"jsmith@example.com"},'
            .'"meta_data":{"foo":"bar"}}';
        static::assertSame($expected, json_encode($this->message, JSON_UNESCAPED_SLASHES));
    }

    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $state = $serializer->serialize($this->message);
        /** @var EventMessage $message */
        $message = $serializer->deserialize($state);
        static::assertTrue($message->equals($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        static::assertSame(0, $this->message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $message = EventMessage::arrayDeserialize($this->getMessageData());
        static::assertSame(0, $this->message->compareTo($message));
    }

    public function test_that_compare_to_returns_one_for_greater_instance()
    {
        $message = EventMessage::arrayDeserialize($this->getAltMessageData());
        static::assertSame(1, $message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_instance()
    {
        $message = EventMessage::arrayDeserialize($this->getAltMessageData());
        static::assertSame(-1, $this->message->compareTo($message));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        static::assertTrue($this->message->equals($this->message));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $message = EventMessage::arrayDeserialize($this->getMessageData());
        static::assertTrue($this->message->equals($message));
    }

    public function test_that_equals_returns_false_for_invalid_argument()
    {
        static::assertFalse($this->message->equals(null));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $message = EventMessage::arrayDeserialize($this->getAltMessageData());
        static::assertFalse($this->message->equals($message));
    }

    public function test_that_hash_value_returns_expected_value()
    {
        static::assertSame(
            sprintf(
                '%s:%s:0150deae68df40caaea16b4b06aadfc3',
                ClassName::short(EventMessage::class),
                ClassName::canonical(MessageId::class)
            ),
            $this->message->hashValue()
        );
    }

    public function test_that_deserialize_throws_exception_for_invalid_data()
    {
        $this->expectException(DomainException::class);

        EventMessage::arrayDeserialize([]);
    }

    public function test_that_deserialize_throws_exception_for_invalid_type()
    {
        $this->expectException(DomainException::class);

        $data = $this->getMessageData();
        $data['type'] = 'command';
        EventMessage::arrayDeserialize($data);
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
            'type'         => 'event',
            'timestamp'    => '2015-11-06T15:23:03.000000[America/Chicago]',
            'payload_type' => 'Novuso.Common.Test.Resources.Domain.Messaging.Event.UserRegisteredEvent',
            'payload'      => [
                'prefix'      => null,
                'first_name'  => 'James',
                'middle_name' => 'D',
                'last_name'   => 'Smith',
                'suffix'      => null,
                'email'       => 'jsmith@example.com'
            ],
            'meta_data'    => ['foo' => 'bar']
        ];
    }

    protected function getAltMessageData()
    {
        return [
            'id'           => '015d7b0a-b036-4d08-b4d9-2284344e69f2',
            'type'         => 'event',
            'timestamp'    => '2017-07-25T18:38:04.000000[America/Chicago]',
            'payload_type' => 'Novuso.Common.Test.Resources.Domain.Messaging.Event.UserRegisteredEvent',
            'payload'      => [
                'prefix'      => null,
                'first_name'  => 'James',
                'middle_name' => 'D',
                'last_name'   => 'Smith',
                'suffix'      => null,
                'email'       => 'jsmith@example.com'
            ],
            'meta_data'    => ['foo' => 'bar']
        ];
    }
}
