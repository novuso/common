<?php

namespace Novuso\Test\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredEvent;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Messaging\Event\EventMessage
 */
class EventMessageTest extends UnitTestCase
{
    /**
     * @var EventMessage
     */
    protected $message;

    protected function setUp()
    {
        $this->message = new EventMessage(
            MessageId::fromString('0150deae-68df-40ca-aea1-6b4b06aadfc3'),
            DateTime::fromString('2015-11-06T15:23:03[America/Chicago]'),
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
        $this->assertSame('jsmith@example.com', $payload->email());
    }

    public function test_that_id_returns_expected_instance()
    {
        $this->assertSame('0150deae-68df-40ca-aea1-6b4b06aadfc3', (string) $this->message->id());
    }

    public function test_that_type_returns_expected_instance()
    {
        $this->assertSame('event', $this->message->type()->value());
    }

    public function test_that_timestamp_returns_expected_instance()
    {
        $this->assertSame('2015-11-06T15:23:03[America/Chicago]', (string) $this->message->timestamp());
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
        $this->assertSame($data, $this->message->payload()->toArray());
    }

    public function test_that_payload_type_returns_expected_instance()
    {
        $expected = 'Novuso.Test.Common.Resources.Domain.Messaging.Event.UserRegisteredEvent';
        $this->assertSame($expected, (string) $this->message->payloadType());
    }

    public function test_that_meta_data_returns_expected_instance()
    {
        $this->assertSame('{"foo":"bar"}', (string) $this->message->metaData());
    }

    public function test_that_with_meta_data_replaces_meta_data()
    {
        $metaData = MetaData::create(['ip_address' => '127.0.0.1']);
        $this->message = $this->message->withMetaData($metaData);
        $this->assertSame('{"ip_address":"127.0.0.1"}', (string) $this->message->metaData());
    }

    public function test_that_merge_meta_data_merges_meta_data()
    {
        $metaData = MetaData::create(['ip_address' => '127.0.0.1']);
        $this->message = $this->message->mergeMetaData($metaData);
        $this->assertSame('{"foo":"bar","ip_address":"127.0.0.1"}', (string) $this->message->metaData());
    }

    public function test_that_to_string_returns_expected_value()
    {
        $expected = '{id:0150deae-68df-40ca-aea1-6b4b06aadfc3,'
            .'type:event,'
            .'timestamp:2015-11-06T15:23:03[America/Chicago],'
            .'payload_type:Novuso.Test.Common.Resources.Domain.Messaging.Event.UserRegisteredEvent,'
            .'payload:{prefix:NULL,first_name:James,middle_name:D,last_name:Smith,'
            .'suffix:NULL,email:jsmith@example.com},'
            .'meta_data:{foo:bar}}';
        $this->assertSame($expected, $this->message->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $expected = '{id:0150deae-68df-40ca-aea1-6b4b06aadfc3,'
            .'type:event,'
            .'timestamp:2015-11-06T15:23:03[America/Chicago],'
            .'payload_type:Novuso.Test.Common.Resources.Domain.Messaging.Event.UserRegisteredEvent,'
            .'payload:{prefix:NULL,first_name:James,middle_name:D,last_name:Smith,'
            .'suffix:NULL,email:jsmith@example.com},'
            .'meta_data:{foo:bar}}';
        $this->assertSame($expected, (string) $this->message);
    }

    public function test_that_it_is_json_encodable()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"event",'
            .'"timestamp":"2015-11-06T15:23:03[America/Chicago]",'
            .'"payload_type":"Novuso.Test.Common.Resources.Domain.Messaging.Event.UserRegisteredEvent",'
            .'"payload":{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith",'
            .'"suffix":null,"email":"jsmith@example.com"},'
            .'"meta_data":{"foo":"bar"}}';
        $this->assertSame($expected, json_encode($this->message, JSON_UNESCAPED_SLASHES));
    }

    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $state = $serializer->serialize($this->message);
        /** @var EventMessage $message */
        $message = $serializer->deserialize($state);
        $this->assertTrue($message->equals($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $this->assertSame(0, $this->message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $message = EventMessage::deserialize($this->getMessageData());
        $this->assertSame(0, $this->message->compareTo($message));
    }

    public function test_that_compare_to_returns_one_for_greater_instance()
    {
        $message = EventMessage::deserialize($this->getAltMessageData());
        $this->assertSame(1, $message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_instance()
    {
        $message = EventMessage::deserialize($this->getAltMessageData());
        $this->assertSame(-1, $this->message->compareTo($message));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $this->assertTrue($this->message->equals($this->message));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $message = EventMessage::deserialize($this->getMessageData());
        $this->assertTrue($this->message->equals($message));
    }

    public function test_that_equals_returns_false_for_invalid_argument()
    {
        $this->assertFalse($this->message->equals(null));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $message = EventMessage::deserialize($this->getAltMessageData());
        $this->assertFalse($this->message->equals($message));
    }

    public function test_that_hash_value_returns_expected_value()
    {
        $this->assertSame('0150deae68df40caaea16b4b06aadfc3', $this->message->hashValue());
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_deserialize_throws_exception_for_invalid_data()
    {
        EventMessage::deserialize([]);
    }

    /**
     * @expectedException \Novuso\System\Exception\DomainException
     */
    public function test_that_deserialize_throws_exception_for_invalid_type()
    {
        $data = $this->getMessageData();
        $data['type'] = 'command';
        EventMessage::deserialize($data);
    }

    /**
     * @expectedException \AssertionError
     */
    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->message->compareTo(null);
    }

    protected function getMessageData()
    {
        return [
            'id'           => '0150deae-68df-40ca-aea1-6b4b06aadfc3',
            'type'         => 'event',
            'timestamp'    => '2015-11-06T15:23:03[America/Chicago]',
            'payload_type' => 'Novuso.Test.Common.Resources.Domain.Messaging.Event.UserRegisteredEvent',
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
            'timestamp'    => '2017-07-25T18:38:04[America/Chicago]',
            'payload_type' => 'Novuso.Test.Common.Resources.Domain.Messaging.Event.UserRegisteredEvent',
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
