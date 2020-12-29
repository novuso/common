<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpFoundation;

use Novuso\System\Type\Enum;

/**
 * Class HttpMethod
 *
 * @method static HEAD
 * @method static GET
 * @method static POST
 * @method static PUT
 * @method static PATCH
 * @method static DELETE
 * @method static PURGE
 * @method static OPTIONS
 * @method static TRACE
 * @method static CONNECT
 */
final class HttpMethod extends Enum
{
    public const HEAD = 'HEAD';
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';
    public const PURGE = 'PURGE';
    public const OPTIONS = 'OPTIONS';
    public const TRACE = 'TRACE';
    public const CONNECT = 'CONNECT';
}
