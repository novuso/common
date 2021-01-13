<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Messaging;

use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Messaging\MetaData
 */
class MetaDataTest extends UnitTestCase
{
    public function test_that_it_is_empty_by_default()
    {
        $metaData = MetaData::create();

        static::assertTrue($metaData->isEmpty());
    }

    public function test_that_is_empty_returns_false_when_data_is_present()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);

        static::assertFalse($metaData->isEmpty());
    }

    public function test_that_count_returns_expected_count()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);

        static::assertSame(2, count($metaData));
    }

    public function test_that_get_returns_expected_value_for_key()
    {
        $metaData = MetaData::create();
        $metaData->set('username', 'jrnickell');

        static::assertSame('jrnickell', $metaData->get('username'));
    }

    public function test_that_get_returns_default_for_missing_key()
    {
        $metaData = MetaData::create();
        $metaData->set('username', 'jrnickell');

        static::assertSame('localhost', $metaData->get('ip_address', 'localhost'));
    }

    public function test_that_offset_get_returns_expected_value_for_key()
    {
        $metaData = MetaData::create();
        $metaData['username'] = 'jrnickell';

        static::assertSame('jrnickell', $metaData['username']);
    }

    public function test_that_offset_get_returns_null_for_missing_key()
    {
        $metaData = MetaData::create();
        $metaData['username'] = 'jrnickell';

        static::assertNull($metaData['ip_address']);
    }

    public function test_that_has_returns_true_for_matching_key()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);

        static::assertTrue($metaData->has('ip_address'));
    }

    public function test_that_offset_exists_returns_true_for_matching_key()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);

        static::assertTrue(isset($metaData['ip_address']));
    }

    public function test_that_has_returns_false_for_missing_key()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);

        static::assertFalse($metaData->has('location'));
    }

    public function test_that_offset_exists_returns_false_for_missing_key()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);

        static::assertFalse(isset($metaData['location']));
    }

    public function test_that_remove_correctly_removes_by_key()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $metaData->remove('username');

        static::assertFalse($metaData->has('username'));
    }

    public function test_that_offset_unset_correctly_removes_by_key()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        unset($metaData['username']);

        static::assertFalse(isset($metaData['username']));
    }

    public function test_that_keys_returns_expected_list_of_keys()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);

        static::assertSame(['username', 'ip_address'], $metaData->keys());
    }

    public function test_that_merge_correctly_merges_data()
    {
        $metaData = MetaData::create(['username' => 'jrnickell']);
        $metaData->merge(MetaData::create(['ip_address' => '127.0.0.1']));

        static::assertTrue($metaData['username'] === 'jrnickell' && $metaData['ip_address'] === '127.0.0.1');
    }

    public function test_that_to_array_returns_expected_data()
    {
        $data = [
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ];
        $metaData = MetaData::create($data);

        static::assertSame($data, $metaData->toArray());
    }

    public function test_that_to_string_returns_expected_string()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $expected = '{"username":"jrnickell","ip_address":"127.0.0.1"}';

        static::assertSame($expected, $metaData->toString());
    }

    public function test_that_string_cast_returns_expected_string()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $expected = '{"username":"jrnickell","ip_address":"127.0.0.1"}';

        static::assertSame($expected, (string) $metaData);
    }

    public function test_that_it_is_json_encodable()
    {
        $metaData = MetaData::create([
            'credentials' => [
                'username' => 'jrnickell',
                'password' => 'secret'
            ],
            'ip_address'  => '127.0.0.1'
        ]);
        $expected = '{"credentials":{"username":"jrnickell","password":"secret"},"ip_address":"127.0.0.1"}';

        static::assertSame($expected, json_encode($metaData));
    }

    public function test_that_it_is_traversable()
    {
        $metaData = MetaData::create([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);

        $count = 0;
        foreach ($metaData as $key => $value) {
            $count++;
        }

        static::assertSame(2, $count);
    }

    public function test_that_set_throws_exception_for_invalid_value()
    {
        $this->expectException(DomainException::class);

        $metaData = MetaData::create();
        $metaData->set('foo', [new \stdClass()]);
    }
}
