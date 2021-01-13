<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Cache;

use Novuso\Common\Application\Cache\Exception\CacheException;

/**
 * Interface Cache
 */
interface Cache
{
    /**
     * Fetches data from cache or loader function
     *
     * Callback signature:
     *
     * <code>
     * function (): mixed {}
     * </code>
     *
     * @throws CacheException When an error occurs
     */
    public function read(string $key, callable $loader, int $ttl): mixed;
}
