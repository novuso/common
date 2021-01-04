<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Resources\Domain\Identity;

use Novuso\Common\Domain\Identity\IntegerId;
use Novuso\System\Exception\DomainException;

/**
 * Class NumericId
 */
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
