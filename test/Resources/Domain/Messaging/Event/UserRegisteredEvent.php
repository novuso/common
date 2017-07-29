<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\EventInterface;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\VarPrinter;

class UserRegisteredEvent implements EventInterface
{
    private $prefix;
    private $firstName;
    private $middleName;
    private $lastName;
    private $suffix;
    private $email;

    public function __construct(
        string $email,
        string $firstName,
        string $lastName,
        string $middleName = null,
        string $prefix = null,
        string $suffix = null
    ) {
        $this->prefix = $prefix;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->suffix = $suffix;
        $this->email = $email;
    }

    public static function fromArray(array $data): UserRegisteredEvent
    {
        $keys = ['prefix', 'first_name', 'middle_name', 'last_name', 'suffix', 'email'];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                $message = sprintf('Invalid serialization format: %s', VarPrinter::toString($data));
                throw new DomainException($message);
            }
        }

        return new static(
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $data['middle_name'],
            $data['prefix'],
            $data['suffix']
        );
    }

    public function prefix()
    {
        return $this->prefix;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function middleName()
    {
        return $this->middleName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function suffix()
    {
        return $this->suffix;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function toArray(): array
    {
        return [
            'prefix'      => $this->prefix,
            'first_name'  => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name'   => $this->lastName,
            'suffix'      => $this->suffix,
            'email'       => $this->email
        ];
    }
}
