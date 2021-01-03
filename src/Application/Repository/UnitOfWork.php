<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Repository;

use Exception;

/**
 * Interface UnitOfWork
 */
interface UnitOfWork
{
    /**
     * Commits the unit of work
     *
     * @throws Exception When an error occurs
     */
    public function commit(): void;

    /**
     * Wraps an operation in a transaction
     *
     * Returns any non-empty result from the operation call.
     *
     * @throws Exception When an error occurs
     */
    public function commitTransactional(callable $operation): mixed;

    /**
     * Checks whether the unit of work is closed
     */
    public function isClosed(): bool;
}
