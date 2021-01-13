<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Templating\Exception;

use Novuso\Common\Application\Templating\Exception\TemplateNotFoundException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Templating\Exception\TemplateNotFoundException
 */
class TemplateNotFoundExceptionTest extends UnitTestCase
{
    public function test_that_from_name_returns_expected_instance()
    {
        $exception = TemplateNotFoundException::fromName('default/index.html.twig');

        static::assertSame('default/index.html.twig', $exception->getTemplate());
    }
}
