<?php

namespace Novuso\Test\Common\Resources\Domain\Model;

use Novuso\System\Exception\DomainException;
use Novuso\Common\Domain\Model\StringId;

class Isbn extends StringId
{
    protected function guardId(string $id)
    {
        if (!preg_match('/\A[0-9]{3}-[0-9]{10}\z/', $id)) {
            $message = sprintf('Invalid ISBN: %s', $id);
            throw new DomainException($message);
        }
    }
}
