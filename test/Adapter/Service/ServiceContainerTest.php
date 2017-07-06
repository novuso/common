<?php

namespace Novuso\Test\Common\Adapter\Service;

use DateTime;
use Novuso\Common\Adapter\Service\ServiceContainer;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Adapter\Service\ServiceContainer
 */
class ServiceContainerTest extends UnitTestCase
{
    protected $container;

    protected function setUp()
    {
        $this->container = new ServiceContainer();
    }

    public function test_that_factory_defines_an_object_factory_service()
    {
        $this->container->factory('date', function () {
            return new DateTime('2015-01-01');
        });
        $date1 = $this->container->get('date');
        $date1->modify('+1 day');
        $date2 = $this->container->get('date');
        $this->assertTrue(
            '2015-01-02' === $date1->format('Y-m-d') && '2015-01-01' === $date2->format('Y-m-d'),
            'Factories should create a new instance with each get() call'
        );
    }

    public function test_that_set_defines_a_shared_service_factory()
    {
        $this->container->set('date', function () {
            return new DateTime('2015-01-01');
        });
        $date1 = $this->container->get('date');
        $date1->modify('+1 day');
        $date2 = $this->container->get('date');
        $this->assertTrue(
            '2015-01-02' === $date1->format('Y-m-d') && '2015-01-02' === $date2->format('Y-m-d'),
            'Services should return the same instance with each get() call'
        );
    }

    public function test_that_remove_correctly_removes_service()
    {
        $this->container->set('date', function () {
            return new DateTime('2015-01-01');
        });
        $this->container->remove('date');
        $this->assertFalse($this->container->has('date'));
    }

    public function test_that_parameters_can_be_set_for_configuration()
    {
        $this->container['date'] = '2015-01-01';
        $this->container->factory('date', function ($container) {
            return new DateTime($container['date']);
        });
        $date = $this->container->get('date');
        $this->assertSame('2015-01-01', $date->format('Y-m-d'));
    }

    public function test_that_undefined_parameter_returns_null_by_default()
    {
        $this->assertNull($this->container['foo']);
    }

    public function test_that_undefined_parameter_returns_custom_default()
    {
        $this->assertTrue($this->container->getParameter('enabled', true));
    }

    public function test_that_remove_parameter_correctly_removes_parameter()
    {
        $this->container['date'] = '2015-01-01';
        unset($this->container['date']);
        $this->assertFalse(isset($this->container['date']));
    }

    /**
     * @expectedException \Novuso\Common\Adapter\Service\Exception\ServiceNotFoundException
     */
    public function test_that_get_throws_exception_for_undefined_service()
    {
        $this->container->get('foo');
    }
}
