<?php

namespace Novuso\Test\Common\Resources\Domain\Messaging\Query;

use Exception;
use Novuso\Common\Domain\Messaging\Query\QueryHandlerInterface;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;

class UserByEmailHandler implements QueryHandlerInterface
{
    protected $users = [
        [
            'prefix'      => null,
            'first_name'  => 'James',
            'middle_name' => 'D',
            'last_name'   => 'Smith',
            'suffix'      => null,
            'email'       => 'jsmith@example.com',
            'password'    => '$2y$10$NXQfVDFu3.Tyd97bnm4TPO/jdrbgL918xeXM5USqfB.qIHFB6mgjC'
        ]
    ];

    public static function queryRegistration(): string
    {
        return UserByEmailQuery::class;
    }

    public function handle(QueryMessage $message)
    {
        /** @var UserByEmailQuery $query */
        $query = $message->payload();
        $email = $query->email();
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        $message = sprintf('User with email "%s" not found', $email);
        throw new Exception($message);
    }
}
