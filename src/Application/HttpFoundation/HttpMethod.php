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
    /**
     * HEAD method
     *
     * @var string
     */
    public const HEAD = 'HEAD';

    /**
     * GET method
     *
     * @var string
     */
    public const GET = 'GET';

    /**
     * POST method
     *
     * @var string
     */
    public const POST = 'POST';

    /**
     * PUT method
     *
     * @var string
     */
    public const PUT = 'PUT';

    /**
     * PATCH method
     *
     * @var string
     */
    public const PATCH = 'PATCH';

    /**
     * DELETE method
     *
     * @var string
     */
    public const DELETE = 'DELETE';

    /**
     * PURGE method
     *
     * @var string
     */
    public const PURGE = 'PURGE';

    /**
     * OPTIONS method
     *
     * @var string
     */
    public const OPTIONS = 'OPTIONS';

    /**
     * TRACE method
     *
     * @var string
     */
    public const TRACE = 'TRACE';

    /**
     * CONNECT method
     *
     * @var string
     */
    public const CONNECT = 'CONNECT';
}
