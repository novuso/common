<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\Validation;

use Mockery\MockInterface;
use Novuso\Common\Application\Validation\Data\InputData;
use Novuso\Common\Application\Validation\ValidationContext;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Validation\ValidationContext
 */
class ValidationContextTest extends UnitTestCase
{
    /** @var ValidationContext */
    protected $context;
    /** @var InputData|MockInterface */
    protected $mockInput;

    protected function setUp(): void
    {
        $this->mockInput = $this->mock(InputData::class);
        $this->context = new ValidationContext($this->mockInput);
    }

    public function test_that_get_delegates_to_input()
    {
        $this->mockInput
            ->shouldReceive('get')
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $this->assertSame('bar', $this->context->get('foo'));
    }

    public function test_that_has_errors_returns_false_when_errors_are_not_present()
    {
        $this->assertFalse($this->context->hasErrors());
    }

    public function test_that_adding_errors_works_as_expected()
    {
        $expected = [
            'foo' => [
                'Foo is required',
                'Foo must be at least 3 characters in length'
            ],
            'bar' => [
                'Bar is required'
            ]
        ];

        $this->context->addError('foo', 'Foo is required');
        $this->context->addError('foo', 'Foo must be at least 3 characters in length');
        $this->context->addError('bar', 'Bar is required');

        // error messages are a set, so this is not added again
        $this->context->addError('foo', 'Foo is required');

        $this->assertSame($expected, $this->context->getErrors());
    }
}
