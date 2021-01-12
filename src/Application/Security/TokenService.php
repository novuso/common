<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Security;

use Novuso\Common\Domain\Value\DateTime\DateTime;

/**
 * Class TokenService
 */
final class TokenService implements TokenDecoder, TokenEncoder
{
    /**
     * Constructs TokenService
     */
    public function __construct(
        protected TokenEncoder $tokenEncoder,
        protected TokenDecoder $tokenDecoder
    ) {
    }

    /**
     * @inheritDoc
     */
    public function decode(string $token): array
    {
        return $this->tokenDecoder->decode($token);
    }

    /**
     * @inheritDoc
     */
    public function encode(array $claims, DateTime $expiration): string
    {
        return $this->tokenEncoder->encode($claims, $expiration);
    }
}
