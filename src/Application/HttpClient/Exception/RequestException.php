<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Throwable;

/**
 * Class RequestException
 */
class RequestException extends TransferException implements RequestExceptionInterface
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
     * @param Throwable|null  $previous The previous exception
     */
    public function __construct(string $message, RequestInterface $request, Throwable $previous = null)
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
