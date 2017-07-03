<?php

namespace Novuso\Test\Common\Resources\Domain\Model;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;

class FullName extends ValueObject
{
    protected $first;
    protected $last;
    protected $middle;

    protected function __construct(string $first, string $last, string $middle = null)
    {
        $this->first = $first;
        $this->last = $last;
        $this->middle = $middle;
    }

    public static function fromString(string $value): FullName
    {
        $parts = explode(' ', trim($value));

        if (count($parts) < 2) {
            $message = sprintf('%s expects first and last name', __METHOD__);
            throw new DomainException($message);
        }

        if (count($parts) === 2) {
            return new static($parts[0], $parts[1]);
        }

        return new static($parts[0], $parts[2], $parts[1]);
    }

    public static function fromParts(string $first, string $last, string $middle = null): FullName
    {
        return new static($first, $last, $middle);
    }

    public function first(): string
    {
        return $this->first;
    }

    public function last(): string
    {
        return $this->last;
    }

    public function middle()
    {
        return $this->middle;
    }

    public function toString(): string
    {
        if ($this->middle !== null) {
            return sprintf('%s %s %s', $this->first, $this->middle, $this->last);
        }

        return sprintf('%s %s', $this->first, $this->last);
    }
}
