<?php declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\VarPrinter;

/**
 * Class UserByEmailQuery
 */
class UserByEmailQuery implements Query
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromArray(array $data): static
    {
        $keys = ['email'];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                $message = sprintf('Invalid serialization format: %s', VarPrinter::toString($data));
                throw new DomainException($message);
            }
        }

        return new static($data['email']);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function toArray(): array
    {
        return ['email' => $this->email];
    }
}
