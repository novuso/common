<?php declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\Common\Test\Resources\Domain\Messaging\Query\UserByEmailQuery;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Messaging\Query\QueryMessage
 */
class QueryMessageTest extends UnitTestCase
{
    /** @var QueryMessage */
    protected $message;

    protected function setUp(): void
    {
        $this->message = new QueryMessage(
            MessageId::fromString('0150deae-68df-40ca-aea1-6b4b06aadfc3'),
            DateTime::fromString('2015-11-06T15:23:03.000000[America/Chicago]'),
            UserByEmailQuery::fromArray(['email' => 'jsmith@example.com']),
            MetaData::create(['foo' => 'bar'])
        );
    }

    public function test_that_create_returns_message_from_query()
    {
        $data = $this->getMessageData();
        $query = UserByEmailQuery::fromArray($data['payload']);
        $message = QueryMessage::create($query);
        /** @var UserByEmailQuery $payload */
        $payload = $message->payload();
        $this->assertSame('jsmith@example.com', $payload->email());
    }

    public function test_that_id_returns_expected_instance()
    {
        $this->assertSame('0150deae-68df-40ca-aea1-6b4b06aadfc3', (string) $this->message->id());
    }

    public function test_that_type_returns_expected_instance()
    {
        $this->assertSame('query', $this->message->type()->value());
    }

    public function test_that_timestamp_returns_expected_instance()
    {
        $this->assertSame('2015-11-06T15:23:03.000000[America/Chicago]', (string) $this->message->timestamp());
    }

    public function test_that_payload_returns_expected_instance()
    {
        $data = ['email' => 'jsmith@example.com'];
        $this->assertSame($data, $this->message->payload()->toArray());
    }

    public function test_that_payload_type_returns_expected_instance()
    {
        $expected = 'Novuso.Common.Test.Resources.Domain.Messaging.Query.UserByEmailQuery';
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
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"query",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Query.UserByEmailQuery",'
            .'"payload":{"email":"jsmith@example.com"},'
            .'"meta_data":{"foo":"bar"}}';
        $this->assertSame($expected, $this->message->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"query",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Query.UserByEmailQuery",'
            .'"payload":{"email":"jsmith@example.com"},'
            .'"meta_data":{"foo":"bar"}}';
        $this->assertSame($expected, (string) $this->message);
    }

    public function test_that_it_is_json_encodable()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"query",'
            .'"timestamp":"2015-11-06T15:23:03.000000[America/Chicago]",'
            .'"payload_type":"Novuso.Common.Test.Resources.Domain.Messaging.Query.UserByEmailQuery",'
            .'"payload":{"email":"jsmith@example.com"},'
            .'"meta_data":{"foo":"bar"}}';
        $this->assertSame($expected, json_encode($this->message, JSON_UNESCAPED_SLASHES));
    }

    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $state = $serializer->serialize($this->message);
        /** @var QueryMessage $message */
        $message = $serializer->deserialize($state);
        $this->assertTrue($message->equals($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $this->assertSame(0, $this->message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $message = QueryMessage::arrayDeserialize($this->getMessageData());
        $this->assertSame(0, $this->message->compareTo($message));
    }

    public function test_that_compare_to_returns_one_for_greater_instance()
    {
        $message = QueryMessage::arrayDeserialize($this->getAltMessageData());
        $this->assertSame(1, $message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_instance()
    {
        $message = QueryMessage::arrayDeserialize($this->getAltMessageData());
        $this->assertSame(-1, $this->message->compareTo($message));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $this->assertTrue($this->message->equals($this->message));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $message = QueryMessage::arrayDeserialize($this->getMessageData());
        $this->assertTrue($this->message->equals($message));
    }

    public function test_that_equals_returns_false_for_invalid_argument()
    {
        $this->assertFalse($this->message->equals(null));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $message = QueryMessage::arrayDeserialize($this->getAltMessageData());
        $this->assertFalse($this->message->equals($message));
    }

    public function test_that_hash_value_returns_expected_value()
    {
        $this->assertSame('0150deae68df40caaea16b4b06aadfc3', $this->message->hashValue());
    }

    public function test_that_deserialize_throws_exception_for_invalid_data()
    {
        $this->expectException(DomainException::class);

        QueryMessage::arrayDeserialize([]);
    }

    public function test_that_deserialize_throws_exception_for_invalid_type()
    {
        $this->expectException(DomainException::class);

        $data = $this->getMessageData();
        $data['type'] = 'event';
        QueryMessage::arrayDeserialize($data);
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
            'type'         => 'query',
            'timestamp'    => '2015-11-06T15:23:03.000000[America/Chicago]',
            'payload_type' => 'Novuso.Common.Test.Resources.Domain.Messaging.Query.UserByEmailQuery',
            'payload'      => ['email' => 'jsmith@example.com'],
            'meta_data'    => ['foo' => 'bar']
        ];
    }

    protected function getAltMessageData()
    {
        return [
            'id'           => '015d7b13-db6a-4389-8164-130e62510cae',
            'type'         => 'query',
            'timestamp'    => '2017-07-25T18:48:05.000000[America/Chicago]',
            'payload_type' => 'Novuso.Common.Test.Resources.Domain.Messaging.Query.UserByEmailQuery',
            'payload'      => ['email' => 'jsmith@example.com'],
            'meta_data'    => ['foo' => 'bar']
        ];
    }
}
