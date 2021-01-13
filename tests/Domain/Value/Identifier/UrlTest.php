<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\Identifier;

use Novuso\Common\Domain\Value\Identifier\Url;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\Identifier\Url
 * @covers \Novuso\Common\Domain\Value\Identifier\Uri
 */
class UrlTest extends UnitTestCase
{
    public function test_that_parse_returns_expected_instance_with_default_port()
    {
        $url = Url::parse('https://www.google.com:443');

        static::assertSame('https://www.google.com', $url->toString());
    }

    public function test_that_parse_returns_expected_instance_empty_query()
    {
        $url = Url::parse('https://app.dev?');

        static::assertSame('', $url->query());
    }

    public function test_that_query_is_normalized_and_ordered_by_key()
    {
        $url1 = Url::parse('https://app.dev?one=two&foo=bar&key=value&=nokey');
        $url2 = Url::parse('https://app.dev?key=value&one=two&foo=bar&');

        static::assertTrue($url1->equals($url2));
    }
}
