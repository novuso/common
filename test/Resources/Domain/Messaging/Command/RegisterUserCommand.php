<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\CommandInterface;
use Novuso\System\Exception\DomainException;

class RegisterUserCommand implements CommandInterface
{
    private $prefix;
    private $firstName;
    private $middleName;
    private $lastName;
    private $suffix;
    private $email;
    private $password;

    public static function fromArray(array $data): RegisterUserCommand
    {
        $keys = ['prefix', 'first_name', 'middle_name', 'last_name', 'suffix', 'email', 'password'];
        foreach ($keys as $key) {
            if (!(isset($data[$key]) || array_key_exists($key, $data))) {
                $message = sprintf('Array missing key: %s', $key);
                throw new DomainException($message);
            }
        }
        /** @var RegisterUserCommand $command */
        $command = new static();
        $command
            ->setPrefix($data['prefix'])
            ->setFirstName($data['first_name'])
            ->setMiddleName($data['middle_name'])
            ->setLastName($data['last_name'])
            ->setSuffix($data['suffix'])
            ->setEmail($data['email'])
            ->setPasswordHash($data['password']);

        return $command;
    }

    public function setPrefix(string $prefix = null): RegisterUserCommand
    {
        if ($prefix === null) {
            $this->prefix = null;
        } else {
            $this->prefix = trim((string) $prefix);
        }

        return $this;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setFirstName(string $firstName): RegisterUserCommand
    {
        $this->firstName = trim((string) $firstName);

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setMiddleName(string $middleName = null): RegisterUserCommand
    {
        if ($middleName === null) {
            $this->middleName = null;
        } else {
            $this->middleName = trim((string) $middleName);
        }

        return $this;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }

    public function setLastName(string $lastName): RegisterUserCommand
    {
        $this->lastName = trim((string) $lastName);

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setSuffix(string $suffix = null): RegisterUserCommand
    {
        if ($suffix === null) {
            $this->suffix = null;
        } else {
            $this->suffix = trim((string) $suffix);
        }

        return $this;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }

    public function setEmail(string $email): RegisterUserCommand
    {
        $this->email = trim((string) $email);

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword(string $password): RegisterUserCommand
    {
        $this->password = password_hash(trim($password), PASSWORD_DEFAULT);

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'prefix'      => $this->prefix,
            'first_name'  => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name'   => $this->lastName,
            'suffix'      => $this->suffix,
            'email'       => $this->email,
            'password'    => $this->password
        ];
    }

    private function setPasswordHash(string $password): RegisterUserCommand
    {
        $this->password = trim($password);

        return $this;
    }
}
