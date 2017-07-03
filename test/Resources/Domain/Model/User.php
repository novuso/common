<?php

namespace Novuso\Test\Common\Resources\Domain\Model;

use Novuso\Common\Domain\Aggregate\AggregateRoot;
use Novuso\Common\Domain\Aggregate\EventRecords;
use Novuso\Test\Common\Resources\Domain\Messaging\Event\UserRegisteredEvent;

class User implements AggregateRoot
{
    use EventRecords;

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

    public static function register(
        string $email,
        string $firstName,
        string $lastName,
        string $middleName = null,
        string $prefix = null,
        string $suffix = null
    ): User {
        $user = new static($email, $firstName, $lastName, $middleName, $prefix, $suffix);

        $userRegisteredEvent = new UserRegisteredEvent(
            $email,
            $firstName,
            $lastName,
            $middleName,
            $prefix,
            $suffix
        );
        $user->recordEvent($userRegisteredEvent);

        return $user;
    }
}
