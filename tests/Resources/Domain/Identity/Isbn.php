<?php declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Identity;

use Novuso\Common\Domain\Identity\StringId;
use Novuso\System\Exception\DomainException;

/**
 * Class Isbn
 */
class Isbn extends StringId
{
    protected function guardId(string $id): void
    {
        if (!preg_match('/\A[0-9]{3}-[0-9]{10}\z/', $id)) {
            $message = sprintf('Invalid ISBN: %s', $id);
            throw new DomainException($message);
        }
    }
}
