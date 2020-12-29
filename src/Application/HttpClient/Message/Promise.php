<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Message;

use Novuso\System\Exception\MethodCallException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Interface Promise
 */
interface Promise
{
    public const PENDING = 'pending';
    public const FULFILLED = 'fulfilled';
    public const REJECTED = 'rejected';

    /**
     * Adds callbacks for when the promise is resolved or rejected
     */
    public function then(?callable $onFulfilled = null, ?callable $onRejected = null): static;

    /**
     * Retrieves the state
     */
    public function getState(): string;

    /**
     * Retrieves the value when fulfilled
     *
     * @throws MethodCallException When the promise is not fulfilled
     */
    public function getResponse(): ResponseInterface;

    /**
     * Retrieves the reason when rejected
     *
     * @throws MethodCallException When the promise is not rejected
     */
    public function getException(): Throwable;

    /**
     * Waits for the promise to be fulfilled or rejected
     */
    public function wait(): void;
}
