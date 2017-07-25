<?php declare(strict_types=1);

namespace Novuso\Common\Application\Http\Message;

use Exception;
use Novuso\System\Exception\MethodCallException;
use Psr\Http\Message\ResponseInterface;

/**
 * PromiseInterface is an extension of the promises/a+ specification
 *
 * @link      https://promisesaplus.com/ Promises/A+
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface PromiseInterface
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
     * @return PromiseInterface
     */
    public function then(?callable $onFulfilled = null, ?callable $onRejected = null): PromiseInterface;

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
     * @return Exception
     *
     * @throws MethodCallException When the promise is not rejected
     */
    public function getException(): Exception;

    /**
     * Waits for the promise to be fulfilled or rejected
     *
     * @return void
     */
    public function wait(): void;
}
