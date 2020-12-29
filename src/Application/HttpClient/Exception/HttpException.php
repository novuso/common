<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Class HttpException
 */
class HttpException extends RequestException
{
    protected ResponseInterface $response;
    protected int $statusCode;

    /**
     * Constructs HttpException
     */
    public function __construct(
        string $message,
        RequestInterface $request,
        ResponseInterface $response,
        ?Throwable $previous = null
    ) {
        $this->response = $response;
        parent::__construct($message, $request, $previous);
        $this->statusCode = $response->getStatusCode();
    }

    /**
     * Creates instance with a normalized error message
     */
    public static function create(
        RequestInterface $request,
        ResponseInterface $response,
        ?Throwable $previous = null
    ): static {
        $message = sprintf(
            '[url]:%s [http method]:%s [status code]:%s [reason phrase]:%s',
            $request->getRequestTarget(),
            $request->getMethod(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        return new static($message, $request, $response, $previous);
    }

    /**
     * Retrieves the response
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Retrieves the status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
