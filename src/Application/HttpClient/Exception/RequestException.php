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
    protected RequestInterface $request;

    /**
     * Constructs RequestException
     */
    public function __construct(string $message, RequestInterface $request, ?Throwable $previous = null)
    {
        $this->request = $request;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Retrieves the request
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
