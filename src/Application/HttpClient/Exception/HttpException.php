<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HttpException thrown when a response is received but the request failed
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class HttpException extends RequestException
{
    /**
     * Response
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Constructs HttpException
     *
     * @param string            $message  The message
     * @param RequestInterface  $request  The request
     * @param ResponseInterface $response The response
     * @param \Throwable|null   $previous The previous exception
     */
    public function __construct(
        string $message,
        RequestInterface $request,
        ResponseInterface $response,
        \Throwable $previous = null
    ) {
        $this->response = $response;
        parent::__construct($message, $request, $previous);
        $this->code = $response->getStatusCode();
    }

    /**
     * Creates instance with a normalized error message
     *
     * @param RequestInterface  $request  The request
     * @param ResponseInterface $response The response
     * @param \Throwable|null   $previous The previous exception
     *
     * @return HttpException
     */
    public static function create(
        RequestInterface $request,
        ResponseInterface $response,
        \Throwable $previous = null
    ): HttpException {
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
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
