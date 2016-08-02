<?php

namespace Novuso\Test\Common\Resources\Domain\Specification;

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
