<?php declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Specification;

use Novuso\Common\Test\Resources\Domain\Specification\Username;
use Novuso\Common\Test\Resources\Domain\Specification\UsernameIsAlphaOnly;
use Novuso\Common\Test\Resources\Domain\Specification\UsernameIsUnique;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Specification\AndSpecification
 * @covers \Novuso\Common\Domain\Specification\CompositeSpecification
 * @covers \Novuso\Common\Domain\Specification\NotSpecification
 * @covers \Novuso\Common\Domain\Specification\OrSpecification
 */
class SpecificationTest extends UnitTestCase
{
    public function test_that_and_spec_returns_true_when_both_valid()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $username = new Username('georgejones');

        $this->assertTrue($usernameIsUnique->and($usernameIsAlphaOnly)->isSatisfiedBy($username));
    }

    public function test_that_and_spec_returns_false_when_one_invalid()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $username = new Username('johnnickell');

        $this->assertFalse($usernameIsUnique->and($usernameIsAlphaOnly)->isSatisfiedBy($username));
    }

    public function test_that_or_spec_returns_true_when_either_valid()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $username = new Username('johnnickell');

        $this->assertTrue($usernameIsUnique->or($usernameIsAlphaOnly)->isSatisfiedBy($username));
    }

    public function test_that_or_spec_returns_false_when_both_invalid()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $username = new Username('admin123');

        $this->assertFalse($usernameIsUnique->or($usernameIsAlphaOnly)->isSatisfiedBy($username));
    }

    public function test_that_not_spec_flips_meaning_of_a_spec()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $username = new Username('user2015');

        $this->assertTrue($usernameIsUnique->and($usernameIsAlphaOnly)->not()->isSatisfiedBy($username));
    }
}
