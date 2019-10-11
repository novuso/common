<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Config;

use Novuso\Common\Application\Config\ConfigContainer;
use Novuso\Common\Application\Config\Exception\FrozenContainerException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Config\ConfigContainer
 */
class ConfigContainerTest extends UnitTestCase
{
    public function test_that_it_is_empty_by_default()
    {
        $config = new ConfigContainer();
        $this->assertTrue($config->isEmpty());
    }

    public function test_that_it_is_not_frozen_by_default()
    {
        $config = new ConfigContainer();
        $this->assertFalse($config->isFrozen());
    }

    public function test_that_count_returns_top_level_count()
    {
        $config = new ConfigContainer($this->getNestedConfig());
        $this->assertCount(5, $config);
    }

    public function test_that_set_get_has_remove_work_as_expected()
    {
        $config = new ConfigContainer();
        $config->set('site_name', 'Website');
        $this->assertSame('Website', $config->get('site_name'));
        $this->assertTrue($config->has('site_name'));
        $config->remove('site_name');
        $this->assertFalse($config->has('site_name'));
        $this->assertNull($config->get('site_name'));
    }

    public function test_that_array_access_works_as_expected()
    {
        $config = new ConfigContainer();
        $config['site_name'] = 'Website';
        $this->assertSame('Website', $config['site_name']);
        $this->assertTrue(isset($config['site_name']));
        unset($config['site_name']);
        $this->assertFalse(isset($config['site_name']));
        $this->assertNull($config['site_name']);
    }

    public function test_that_array_appending_works_as_expected()
    {
        $config = new ConfigContainer();
        $config[] = 'foo';
        $this->assertSame('foo', $config[0]);
    }

    public function test_that_clone_properly_clones_children()
    {
        $config = new ConfigContainer($this->getNestedConfig());
        $newConfig = clone $config;
        $newConfig['database']['connections']['default']->set('host', 'localhost');
        $this->assertSame('127.0.0.1', $config['database']['connections']['default']['host']);
    }

    public function test_that_keys_returns_expected_value()
    {
        $config = new ConfigContainer($this->getNestedConfig());
        $this->assertSame([0, 1, 2, 'foo', 'database'], $config->keys());
    }

    public function test_that_merge_properly_merges_configuration()
    {
        $config1 = new ConfigContainer($this->getNestedConfig());
        $config2 = new ConfigContainer($this->getMergeConfig());
        $config = $config1->merge($config2);
        $this->assertSame($this->getCombinedConfig(), $config->toArray());
    }

    public function test_that_set_throws_exception_when_frozen()
    {
        $this->expectException(FrozenContainerException::class);

        $config = new ConfigContainer();
        $config->freeze();
        $config->set('foo', 'bar');
    }

    public function test_that_set_throws_exception_on_children_when_frozen()
    {
        $this->expectException(FrozenContainerException::class);

        $config = new ConfigContainer($this->getNestedConfig());
        $config->freeze();
        $config['database']['connections']['default']->set('host', 'localhost');
    }

    public function test_that_remove_throws_exception_when_frozen()
    {
        $this->expectException(FrozenContainerException::class);

        $config = new ConfigContainer();
        $config->freeze();
        $config->remove('foo');
    }

    public function test_that_merge_throws_exception_when_this_container_is_frozen()
    {
        $this->expectException(FrozenContainerException::class);

        $config1 = new ConfigContainer($this->getNestedConfig());
        $config1->freeze();
        $config2 = new ConfigContainer($this->getMergeConfig());
        $config1->merge($config2);
    }

    public function test_that_merge_throws_exception_when_other_container_is_frozen()
    {
        $this->expectException(FrozenContainerException::class);

        $config1 = new ConfigContainer($this->getNestedConfig());
        $config2 = new ConfigContainer($this->getMergeConfig());
        $config2->freeze();
        $config1->merge($config2);
    }

    protected function getNestedConfig()
    {
        return [
            0          => 'zero',
            1          => 'one',
            2          => 'two',
            'foo'      => 'bar',
            'database' => [
                'connections' => [
                    'default' => [
                        'host'     => '127.0.0.1',
                        'user'     => 'username',
                        'password' => 'secret',
                        'dbname'   => 'database'
                    ]
                ]
            ]
        ];
    }

    protected function getMergeConfig()
    {
        return [
            0          => 'three',
            1          => 'four',
            2          => 'five',
            'foo'      => 'buz',
            'database' => [
                'connections' => [
                    'core' => [
                        'host'     => '127.0.0.1',
                        'user'     => 'username',
                        'password' => 'secret',
                        'dbname'   => 'database'
                    ]
                ]
            ]
        ];
    }

    protected function getCombinedConfig()
    {
        return [
            0          => 'zero',
            1          => 'one',
            2          => 'two',
            'foo'      => 'buz',
            'database' => [
                'connections' => [
                    'default' => [
                        'host'     => '127.0.0.1',
                        'user'     => 'username',
                        'password' => 'secret',
                        'dbname'   => 'database'
                    ],
                    'core'    => [
                        'host'     => '127.0.0.1',
                        'user'     => 'username',
                        'password' => 'secret',
                        'dbname'   => 'database'
                    ]
                ]
            ],
            3          => 'three',
            4          => 'four',
            5          => 'five'
        ];
    }
}
