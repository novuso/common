<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Identity;

/**
 * Interface IdentifierFactory
 */
interface IdentifierFactory
{
    /**
     * Generates a unique identifier
     *
     * @return Identifier
     */
    public static function generate();
}
