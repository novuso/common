<?php

namespace Novuso\Test\Common\Resources\Domain\Model;

use Novuso\System\Exception\DomainException;
use Novuso\Common\Domain\Model\IntegerId;

class NumericId extends IntegerId
{
    protected function guardId(int $id)
    {
        if ($id <= 0) {
            $message = sprintf('Invalid ID: %d', $id);
            throw new DomainException($message);
        }
    }
}
