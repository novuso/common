<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\Identifier;

use Novuso\Common\Domain\Value\Identifier\Uri;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\Identifier\Uri
 */
class UriTest extends UnitTestCase
{
    public function test_that_from_string_returns_expected_instance()
    {
        $uri = Uri::fromString('https://www.google.com');

        static::assertSame('https://www.google.com', $uri->toString());
    }

    public function test_that_parse_returns_expected_instance_scheme()
    {
        $uri = Uri::parse('HTTPS://www.google.com');

        static::assertSame('https', $uri->scheme());
    }

    public function test_that_parse_returns_expected_instance_authority()
    {
        $uri = Uri::parse('https://username:password@mydomain.net:8110');

        static::assertSame('username:password@mydomain.net:8110', $uri->authority());
    }

    public function test_that_parse_returns_expected_null_authority()
    {
        $uri = Uri::parse('mailto:smith@example.com');

        static::assertNull($uri->authority());
    }

    public function test_that_parse_returns_expected_empty_authority()
    {
        $uri = Uri::parse('file:///etc/hosts');

        static::assertSame('', $uri->authority());
    }

    public function test_that_parse_returns_expected_instance_user_info()
    {
        $uri = Uri::parse('https://username:password@mydomain.net:8110');

        static::assertSame('username:password', $uri->userInfo());
    }

    public function test_that_parse_returns_expected_null_user_info()
    {
        $uri = Uri::parse('https://mydomain.net:8110');

        static::assertNull($uri->userInfo());
    }

    public function test_that_parse_returns_expected_instance_ipvfuture()
    {
        $uri = Uri::parse('https://[v1a.1080::8:800:200c:417a]/path');

        static::assertSame('[v1a.1080::8:800:200c:417a]', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_ipv6()
    {
        $uri = Uri::parse('https://[1080:0:0:0:8:800:200c:417a]/path');

        static::assertSame('[1080:0:0:0:8:800:200c:417a]', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_ipv4()
    {
        $uri = Uri::parse('https://127.0.0.1/path');

        static::assertSame('127.0.0.1', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_host()
    {
        $uri = Uri::parse('https://username:password@mydomain.net:8110');

        static::assertSame('mydomain.net', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_port()
    {
        $uri = Uri::parse('https://username:password@mydomain.net:8110');

        static::assertSame(8110, $uri->port());
    }

    public function test_that_parse_returns_expected_null_port()
    {
        $uri = Uri::parse('https://username:password@mydomain.net');

        static::assertNull($uri->port());
    }

    public function test_that_parse_returns_expected_missing_port()
    {
        // URI producers and normalizers should omit the ":" delimiter that
        // separates host from port if the port component is empty
        $uri = Uri::parse('https://username:password@mydomain.net:');

        static::assertSame('mydomain.net', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_path()
    {
        $uri = Uri::parse('https://application.net/path/to/file.txt');

        static::assertSame('/path/to/file.txt', $uri->path());
    }

    public function test_that_parse_returns_expected_empty_path()
    {
        $uri = Uri::parse('https://application.net');

        static::assertSame('', $uri->path());
    }

    public function test_that_parse_returns_expected_instance_query()
    {
        $uri = Uri::parse('https://application.net/path?foo=bar&action=seek');

        static::assertSame('foo=bar&action=seek', $uri->query());
    }

    public function test_that_parse_returns_expected_null_query()
    {
        $uri = Uri::parse('https://application.net/path');

        static::assertNull($uri->query());
    }

    public function test_that_parse_returns_expected_empty_query()
    {
        $uri = Uri::parse('https://application.net/path?');

        static::assertSame('', $uri->query());
    }

    public function test_that_parse_returns_expected_encoded_query()
    {
        $uri = Uri::parse('https://application.net/path?q=foo%2Ebar');

        static::assertSame('q=foo.bar', $uri->query());
    }

    public function test_that_parse_returns_expected_instance_fragment()
    {
        $uri = Uri::parse('https://application.net/path#section1.03');

        static::assertSame('section1.03', $uri->fragment());
    }

    public function test_that_parse_returns_expected_null_fragment()
    {
        $uri = Uri::parse('https://application.net/path');

        static::assertNull($uri->fragment());
    }

    public function test_that_parse_returns_expected_empty_fragment()
    {
        $uri = Uri::parse('https://application.net/path#');

        static::assertSame('', $uri->fragment());
    }

    public function test_that_parse_returns_expected_encoded_fragment()
    {
        $uri = Uri::parse('https://application.net/path#%3Cfragment%3E');

        static::assertSame('%3Cfragment%3E', $uri->fragment());
    }

    /**
     * @dataProvider referenceResolutionExamples
     */
    public function test_that_resolve_passes_rfc3986_examples($ref, $expected)
    {
        // http://tools.ietf.org/html/rfc3986#section-5.4
        $base = 'http://a/b/c/d;p?q';
        $uri = Uri::resolve($base, $ref);

        static::assertSame($expected, $uri->toString());
    }

    public function test_that_resolve_passes_rfc3986_non_strict_with_flag()
    {
        // http://tools.ietf.org/html/rfc3986#section-5.4
        $base = 'http://a/b/c/d;p?q';
        $uri = Uri::resolve($base, 'http:g', false);

        static::assertSame('http://a/b/c/g', $uri->toString());
    }

    public function test_that_resolve_returns_expected_instance_empty_base_path()
    {
        $base = 'http://app.dev';
        $uri = Uri::resolve($base, '.');

        static::assertSame('http://app.dev/', $uri->toString());
    }

    public function test_that_resolve_returns_expected_instance_with_null_base_authority()
    {
        $base = 'mailto:smith@example.com';
        $uri = Uri::resolve($base, '.');

        static::assertSame('mailto:', $uri->toString());
    }

    public function test_that_resolve_returns_expected_instance_with_null_base_authority_2()
    {
        $base = 'mailto:smith@example.com';
        $uri = Uri::resolve($base, './');

        static::assertSame('mailto:', $uri->toString());
    }

    public function test_that_resolve_returns_expected_instance_with_null_base_authority_3()
    {
        $base = 'mailto:smith@example.com';
        $uri = Uri::resolve($base, '../');

        static::assertSame('mailto:', $uri->toString());
    }

    public function test_that_from_array_returns_expected_instance()
    {
        $uri = Uri::fromArray([
            'scheme'    => 'http',
            'authority' => 'myapp.com',
            'path'      => '/action',
            'query'     => 'foo=bar',
            'fragment'  => '!wha'
        ]);

        static::assertSame('http://myapp.com/action?foo=bar#!wha', $uri->toString());
    }

    public function test_that_to_array_returns_expected_value()
    {
        $uri = Uri::parse('http://myapp.com/action?foo=bar#!wha');
        $expected = [
            'scheme'    => 'http',
            'authority' => 'myapp.com',
            'path'      => '/action',
            'query'     => 'foo=bar',
            'fragment'  => '!wha'
        ];

        static::assertSame($expected, $uri->toArray());
    }

    public function test_that_with_scheme_returns_expected_instance()
    {
        $uri = Uri::parse('http://myapp.com/action?foo=bar#!wha');
        $uri = $uri->withScheme('https');

        static::assertSame('https://myapp.com/action?foo=bar#!wha', $uri->toString());
    }

    public function test_that_with_authority_returns_expected_instance()
    {
        $uri = Uri::parse('http://myapp.com/action?foo=bar#!wha');
        $uri = $uri->withAuthority('domain.com');

        static::assertSame('http://domain.com/action?foo=bar#!wha', $uri->toString());
    }

    public function test_that_with_path_returns_expected_instance()
    {
        $uri = Uri::parse('http://myapp.com/action?foo=bar#!wha');
        $uri = $uri->withPath('/some/other/path');

        static::assertSame('http://myapp.com/some/other/path?foo=bar#!wha', $uri->toString());
    }

    public function test_that_with_query_returns_expected_instance()
    {
        $uri = Uri::parse('http://myapp.com/action?foo=bar#!wha');
        $uri = $uri->withQuery('baz=buz&key=value');

        static::assertSame('http://myapp.com/action?baz=buz&key=value#!wha', $uri->toString());
    }

    public function test_that_with_fragment_returns_expected_instance()
    {
        $uri = Uri::parse('http://myapp.com/action?foo=bar#!wha');
        $uri = $uri->withFragment('a-really-cool-section');

        static::assertSame('http://myapp.com/action?foo=bar#a-really-cool-section', $uri->toString());
    }

    public function test_that_to_string_returns_user_info()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertSame('https://user:secret@myapp.com:8080/action?foo=bar#!wha', $uri->toString());
    }

    public function test_that_display_does_not_return_user_info()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertSame('https://myapp.com:8080/action?foo=bar#!wha', $uri->display());
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertSame(0, $uri->compareTo($uri));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $uri1 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertSame(0, $uri1->compareTo($uri2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $uri1 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('http://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertSame(1, $uri1->compareTo($uri2));
    }

    public function test_that_compare_to_returns_one_for_lesser_value()
    {
        $uri1 = Uri::parse('http://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertSame(-1, $uri1->compareTo($uri2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertTrue($uri->equals($uri));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $uri1 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertTrue($uri1->equals($uri2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertFalse($uri->equals('https://user:secret@myapp.com:8080/action?foo=bar#!wha'));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $uri1 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('https://other:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertFalse($uri1->equals($uri2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');

        static::assertSame('https://user:secret@myapp.com:8080/action?foo=bar#!wha', $uri->hashValue());
    }

    public function test_that_parse_throws_exception_for_missing_scheme()
    {
        $this->expectException(DomainException::class);

        Uri::parse('/');
    }

    public function test_that_parse_throws_exception_for_invalid_scheme()
    {
        $this->expectException(DomainException::class);

        Uri::parse('ht_tp://www.google.com');
    }

    public function test_that_parse_throws_exception_for_invalid_path()
    {
        $this->expectException(DomainException::class);

        Uri::parse('http://app.dev/some[invalid]');
    }

    public function test_that_parse_throws_exception_for_invalid_query()
    {
        $this->expectException(DomainException::class);

        Uri::parse('http://app.dev/path?foo="bar"');
    }

    public function test_that_parse_throws_exception_for_invalid_fragment()
    {
        $this->expectException(DomainException::class);

        Uri::parse('http://app.dev/path#<foo>');
    }

    public function test_that_parse_throws_exception_for_invalid_user_info()
    {
        $this->expectException(DomainException::class);

        Uri::parse('http://user:pa$$wo<r>d@app.dev/path');
    }

    public function test_that_parse_throws_exception_for_invalid_ip_literal_brackets()
    {
        $this->expectException(DomainException::class);

        Uri::parse('http://[3210]123/path');
    }

    public function test_that_resolve_throws_exception_for_ref_with_first_seg_colon()
    {
        $this->expectException(DomainException::class);

        Uri::resolve('http://app.dev', '/seg:check/path');
    }

    public function test_that_with_path_throws_exception_when_path_is_missing_front_slash_with_host()
    {
        $this->expectException(DomainException::class);

        $uri = Uri::parse('http://app.dev/something');
        $uri->withPath('another/path');
    }

    public function test_that_with_path_throws_exception_when_path_is_invalid_missing_host()
    {
        $this->expectException(DomainException::class);

        $uri = Uri::parse('file:///some_file.txt');
        $uri = $uri->withPath('//some_other_file.txt');

        var_dump($uri->toString());
        exit();
    }

    public function test_that_compare_to_throws_exception_for_invalid_argument()
    {
        $this->expectException(AssertionException::class);

        $uri = Uri::parse('https://www.google.com');
        $uri->compareTo('https://www.google.com');
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-5.4
     *
     * @return array
     */
    public function referenceResolutionExamples()
    {
        return [
            ["g:h", "g:h"],
            ["g", "http://a/b/c/g"],
            ["./g", "http://a/b/c/g"],
            ["g/", "http://a/b/c/g/"],
            ["/g", "http://a/g"],
            ["//g", "http://g"],
            ["?y", "http://a/b/c/d;p?y"],
            ["g?y", "http://a/b/c/g?y"],
            ["#s", "http://a/b/c/d;p?q#s"],
            ["g#s", "http://a/b/c/g#s"],
            ["g?y#s", "http://a/b/c/g?y#s"],
            [";x", "http://a/b/c/;x"],
            ["g;x", "http://a/b/c/g;x"],
            ["g;x?y#s", "http://a/b/c/g;x?y#s"],
            ["", "http://a/b/c/d;p?q"],
            [".", "http://a/b/c/"],
            ["./", "http://a/b/c/"],
            ["..", "http://a/b/"],
            ["../", "http://a/b/"],
            ["../g", "http://a/b/g"],
            ["../..", "http://a/"],
            ["../../", "http://a/"],
            ["../../g", "http://a/g"],
            ["../../../g", "http://a/g"],
            ["../../../../g", "http://a/g"],
            ["/./g", "http://a/g"],
            ["/../g", "http://a/g"],
            ["g.", "http://a/b/c/g."],
            [".g", "http://a/b/c/.g"],
            ["g..", "http://a/b/c/g.."],
            ["..g", "http://a/b/c/..g"],
            ["./../g", "http://a/b/g"],
            ["./g/.", "http://a/b/c/g/"],
            ["g/./h", "http://a/b/c/g/h"],
            ["g/../h", "http://a/b/c/h"],
            ["g;x=1/./y", "http://a/b/c/g;x=1/y"],
            ["g;x=1/../y", "http://a/b/c/y"],
            ["g?y/./x", "http://a/b/c/g?y/./x"],
            ["g?y/../x", "http://a/b/c/g?y/../x"],
            ["g#s/./x", "http://a/b/c/g#s/./x"],
            ["g#s/../x", "http://a/b/c/g#s/../x"],
            ["http:g", "http:g"]
        ];
    }
}
