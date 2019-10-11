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
    /**
     * Pending state
     *
     * @var string
     */
    public const PENDING = 'pending';

    /**
     * Fulfilled state
     *
     * @var string
     */
    public const FULFILLED = 'fulfilled';

    /**
     * Rejected state
     *
     * @var string
     */
    public const REJECTED = 'rejected';

    /**
     * Adds callbacks for when the promise is resolved or rejected
     *
     * @param callable|null $onFulfilled Callback when a response is available
     * @param callable|null $onRejected  Callback when an error occurs
     *
     * @return Promise
     */
    public function then(?callable $onFulfilled = null, ?callable $onRejected = null): Promise;

    /**
     * Retrieves the state
     *
     * @return string
     */
    public function getState(): string;

    /**
     * Retrieves the value when fulfilled
     *
     * @return ResponseInterface
     *
     * @throws MethodCallException When the promise is not fulfilled
     */
    public function getResponse(): ResponseInterface;

    /**
     * Retrieves the reason when rejected
     *
     * @return Throwable
     *
     * @throws MethodCallException When the promise is not rejected
     */
    public function getException(): Throwable;

    /**
     * Waits for the promise to be fulfilled or rejected
     *
     * @return void
     */
    public function wait(): void;
}
