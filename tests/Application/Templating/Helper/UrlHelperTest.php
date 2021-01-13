<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\Templating\Helper;

use Mockery\MockInterface;
use Novuso\Common\Application\Routing\UrlGenerator;
use Novuso\Common\Application\Templating\Helper\UrlHelper;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\Templating\Helper\UrlHelper
 */
class UrlHelperTest extends UnitTestCase
{
    /** @var UrlGenerator|MockInterface */
    protected $mockUrlGenerator;

    public function setUp(): void
    {
        $this->mockUrlGenerator = $this->mock(UrlGenerator::class);
    }

    public function test_that_generate_returns_expected_string()
    {
        $this->mockUrlGenerator
            ->shouldReceive('generate')
            ->once()
            ->with('foo', [], [], false)
            ->andReturn('https://www.google.com/');

        $urlHelper = new UrlHelper($this->mockUrlGenerator);
        $url = $urlHelper->generate('foo');

        static::assertSame('https://www.google.com/', $url);
    }

    public function test_that_get_name_returns_helper_name()
    {
        $urlHelper = new UrlHelper($this->mockUrlGenerator);
        $name = $urlHelper->getName();

        static::assertSame('_url', $name);
    }
}
