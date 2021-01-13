<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Security;

use Novuso\Common\Application\Security\Exception\TokenException;

/**
 * Interface TokenDecoder
 */
interface TokenDecoder
{
    /**
     * Decodes a token into claims
     *
     * @throws TokenException When an error occurs during decoding
     */
    public function decode(string $token): array;
}
