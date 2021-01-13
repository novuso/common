<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Security;

use Novuso\Common\Application\Security\Exception\TokenException;
use Novuso\Common\Domain\Value\DateTime\DateTime;

/**
 * Interface TokenEncoder
 */
interface TokenEncoder
{
    /**
     * Encodes claims into a token
     *
     * @throws TokenException When an error occurs during encoding
     */
    public function encode(array $claims, DateTime $expiration): string;
}
