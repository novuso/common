<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Exception;

use Psr\Http\Client\NetworkExceptionInterface;

/**
 * Class NetworkException
 */
class NetworkException extends RequestException implements NetworkExceptionInterface
{
}
