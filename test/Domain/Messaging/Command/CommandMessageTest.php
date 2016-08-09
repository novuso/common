<?php

namespace Novuso\Test\Common\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\Test\Common\Resources\Domain\Messaging\Command\RegisterUserCommand;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers Novuso\Common\Domain\Messaging\BaseMessage
 * @covers Novuso\Common\Domain\Messaging\Command\CommandMessage
 */
class CommandMessageTest extends UnitTestCase
{
    /**
     * @var CommandMessage
     */
    protected $message;

    protected function setUp()
    {
        $this->message = new CommandMessage(
            MessageId::fromString('0150deae-68df-40ca-aea1-6b4b06aadfc3'),
            DateTime::fromString('2015-11-06T15:23:03[America/Chicago]'),
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
        $this->assertSame('jsmith@example.com', $payload->getEmail());
    }

    public function test_that_id_returns_expected_instance()
    {
        $this->assertSame('0150deae-68df-40ca-aea1-6b4b06aadfc3', (string) $this->message->id());
    }

    public function test_that_type_returns_expected_instance()
    {
        $this->assertSame('command', $this->message->type()->value());
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
            'email'       => 'jsmith@example.com',
            'password'    => '$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC'
        ];
        $this->assertSame($data, $this->message->payload()->toArray());
    }

    public function test_that_payload_type_returns_expected_instance()
    {
        $expected = 'Novuso.Test.Common.Resources.Domain.Messaging.Command.RegisterUserCommand';
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
            .'type:command,'
            .'timestamp:2015-11-06T15:23:03[America/Chicago],'
            .'meta_data:{foo:bar},'
            .'payload_type:Novuso.Test.Common.Resources.Domain.Messaging.Command.RegisterUserCommand,'
            .'payload:{prefix:NULL,first_name:James,middle_name:D,last_name:Smith,'
            .'suffix:NULL,email:jsmith@example.com,'
            .'password:$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC}}';
        $this->assertSame($expected, $this->message->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $expected = '{id:0150deae-68df-40ca-aea1-6b4b06aadfc3,'
            .'type:command,'
            .'timestamp:2015-11-06T15:23:03[America/Chicago],'
            .'meta_data:{foo:bar},'
            .'payload_type:Novuso.Test.Common.Resources.Domain.Messaging.Command.RegisterUserCommand,'
            .'payload:{prefix:NULL,first_name:James,middle_name:D,last_name:Smith,'
            .'suffix:NULL,email:jsmith@example.com,'
            .'password:$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC}}';
        $this->assertSame($expected, (string) $this->message);
    }

    public function test_that_it_is_json_encodable()
    {
        $expected = '{"id":"0150deae-68df-40ca-aea1-6b4b06aadfc3",'
            .'"type":"command",'
            .'"timestamp":"2015-11-06T15:23:03[America/Chicago]",'
            .'"meta_data":{"foo":"bar"},'
            .'"payload_type":"Novuso.Test.Common.Resources.Domain.Messaging.Command.RegisterUserCommand",'
            .'"payload":{"prefix":null,"first_name":"James","middle_name":"D","last_name":"Smith",'
            .'"suffix":null,"email":"jsmith@example.com",'
            .'"password":"$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC"}}';
        $this->assertSame($expected, json_encode($this->message, JSON_UNESCAPED_SLASHES));
    }

    public function test_that_it_is_serializable()
    {
        $state = serialize($this->message);
        /** @var CommandMessage $message */
        $message = unserialize($state);
        $this->assertTrue($message->equals($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $this->assertSame(0, $this->message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $message = $this->message->mergeMetaData(MetaData::create());
        $this->assertSame(0, $this->message->compareTo($message));
    }

    public function test_that_compare_to_returns_one_for_greater_instance()
    {
        $message = $this->message->withMetaData(MetaData::create(['ip_address' => '127.0.0.1']));
        $this->assertSame(1, $message->compareTo($this->message));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_instance()
    {
        $message = $this->message->withMetaData(MetaData::create(['ip_address' => '127.0.0.1']));
        $this->assertSame(-1, $this->message->compareTo($message));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $this->assertTrue($this->message->equals($this->message));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $message = $this->message->mergeMetaData(MetaData::create());
        $this->assertTrue($this->message->equals($message));
    }

    public function test_that_equals_returns_false_for_invalid_argument()
    {
        $this->assertFalse($this->message->equals(null));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $message = $this->message->mergeMetaData(MetaData::create(['ip_address' => '127.0.0.1']));
        $this->assertFalse($this->message->equals($message));
    }

    public function test_that_hash_value_returns_expected_value()
    {
        $this->assertSame($this->message->toString(), $this->message->hashValue());
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
            'type'         => 'command',
            'timestamp'    => '2015-11-06T15:23:03[America/Chicago]',
            'meta_data'    => ['foo' => 'bar'],
            'payload_type' => 'Novuso.Test.Common.Resources.Domain.Messaging.Command.RegisterUserCommand',
            'payload'      => [
                'prefix'      => null,
                'first_name'  => 'James',
                'middle_name' => 'D',
                'last_name'   => 'Smith',
                'suffix'      => null,
                'email'       => 'jsmith@example.com',
                'password'    => '$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC'
            ]
        ];
    }
}
