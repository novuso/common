<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Exception;

use Psr\Http\Message\RequestInterface;

/**
 * RequestException is thrown for failed requests
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class RequestException extends TransferException
{
    /**
     * Request
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Constructs RequestException
     *
     * @param string           $message  The message
     * @param RequestInterface $request  The request
     * @param \Throwable|null  $previous The previous exception
     */
    public function __construct(string $message, RequestInterface $request, \Throwable $previous = null)
    {
        $this->request = $request;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Retrieves the request
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
