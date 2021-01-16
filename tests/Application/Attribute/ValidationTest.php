<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Attribute;

use Novuso\Common\Application\Attribute\Validation;
use Novuso\Common\Test\Resources\Application\TestController;
use Novuso\System\Test\TestCase\UnitTestCase;
use ReflectionClass;

/**
 * @covers \Novuso\Common\Application\Attribute\Validation
 */
class ValidationTest extends UnitTestCase
{
    public function test_that_reflection_returns_expected_instance()
    {
        $reflection = new ReflectionClass(TestController::class);
        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(Validation::class);
            foreach ($attributes as $attribute) {
                /** @var Validation $validation */
                $validation = $attribute->newInstance();
                if ($validation->formName()) {
                    static::assertSame('create-user', $validation->formName());
                    static::assertCount(3, $validation->rules());
                } else {
                    static::assertCount(4, $validation->rules());
                }
            }
        }
    }
}
