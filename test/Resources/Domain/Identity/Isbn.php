<?php

namespace Novuso\Test\Common\Resources\Domain\Identity;

use Novuso\System\Exception\DomainException;
use Novuso\Common\Domain\Identity\StringId;

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