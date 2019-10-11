<?php declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\Communication;

use Novuso\Common\Domain\Value\Communication\EmailAddress;
use Novuso\System\Exception\DomainException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\Communication\EmailAddress
 */
class EmailAddressTest extends UnitTestCase
{
    public function test_that_local_part_returns_expected_value()
    {
        $emailAddress = new EmailAddress('ljenkins@example.com');

        $this->assertSame('ljenkins', $emailAddress->localPart());
    }

    public function test_that_domain_part_returns_expected_value()
    {
        $emailAddress = new EmailAddress('ljenkins@example.com');

        $this->assertSame('example.com', $emailAddress->domainPart());
    }

    public function test_that_domain_part_returns_ip_address_when_domain_is_literal_ip()
    {
        $emailAddress = new EmailAddress('user@[192.168.0.1]');

        $this->assertSame('192.168.0.1', $emailAddress->domainPart());
    }

    public function test_that_canonical_returns_email_address_in_lowercase()
    {
        $emailAddress = new EmailAddress('LJenkins@ExAmPlE.com');

        $this->assertSame('ljenkins@example.com', $emailAddress->canonical());
    }

    public function test_that_to_string_returns_expected_value()
    {
        $emailAddress = EmailAddress::fromString('ljenkins@example.com');

        $this->assertSame('ljenkins@example.com', $emailAddress->toString());
    }

    public function test_that_constructor_throws_exception_when_email_is_invalid()
    {
        $this->expectException(DomainException::class);

        EmailAddress::fromString('ljenkins@example');
    }
}
