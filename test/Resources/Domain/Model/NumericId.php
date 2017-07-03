<?php

namespace Novuso\Test\Common\Resources\Domain\Model;

use Novuso\System\Exception\DomainException;
use Novuso\Common\Domain\Identification\IntegerId;

class NumericId extends IntegerId
{
    protected function guardId(int $id): void
    {
        if ($id <= 0) {
            $message = sprintf('Invalid ID: %d', $id);
            throw new DomainException($message);
        }
    }
}
