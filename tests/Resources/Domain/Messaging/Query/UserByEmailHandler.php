<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Messaging\Query;

use Exception;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;

/**
 * Class UserByEmailHandler
 */
class UserByEmailHandler implements QueryHandler
{
    protected array $users = [
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

    public function handle(QueryMessage $queryMessage): array
    {
        /** @var UserByEmailQuery $query */
        $query = $queryMessage->payload();
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
