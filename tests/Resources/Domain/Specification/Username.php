<?php declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Specification;

/**
 * Class Username
 */
class Username
{
    protected $username;

    public function __construct(string $username)
    {
        $this->username = trim($username);
    }

    public function toString(): string
    {
        return $this->username;
    }
}
