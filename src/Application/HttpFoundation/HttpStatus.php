<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpFoundation;

use Novuso\System\Type\Enum;

/**
 * Class HttpStatus
 *
 * @method static CONTINUE
 * @method static SWITCHING_PROTOCOLS
 * @method static PROCESSING
 * @method static OK
 * @method static CREATED
 * @method static ACCEPTED
 * @method static NON_AUTHORITATIVE_INFORMATION
 * @method static NO_CONTENT
 * @method static RESET_CONTENT
 * @method static PARTIAL_CONTENT
 * @method static MULTI_STATUS
 * @method static ALREADY_REPORTED
 * @method static IM_USED
 * @method static MULTIPLE_CHOICES
 * @method static MOVED_PERMANENTLY
 * @method static FOUND
 * @method static SEE_OTHER
 * @method static NOT_MODIFIED
 * @method static USE_PROXY
 * @method static RESERVED
 * @method static TEMPORARY_REDIRECT
 * @method static PERMANENT_REDIRECT
 * @method static BAD_REQUEST
 * @method static UNAUTHORIZED
 * @method static PAYMENT_REQUIRED
 * @method static FORBIDDEN
 * @method static NOT_FOUND
 * @method static METHOD_NOT_ALLOWED
 * @method static NOT_ACCEPTABLE
 * @method static PROXY_AUTHENTICATION_REQUIRED
 * @method static REQUEST_TIMEOUT
 * @method static CONFLICT
 * @method static GONE
 * @method static LENGTH_REQUIRED
 * @method static PRECONDITION_FAILED
 * @method static REQUEST_ENTITY_TOO_LARGE
 * @method static REQUEST_URI_TOO_LONG
 * @method static UNSUPPORTED_MEDIA_TYPE
 * @method static REQUESTED_RANGE_NOT_SATISFIABLE
 * @method static EXPECTATION_FAILED
 * @method static I_AM_A_TEAPOT
 * @method static ENHANCE_YOUR_CALM
 * @method static MISDIRECTED_REQUEST
 * @method static UNPROCESSABLE_ENTITY
 * @method static LOCKED
 * @method static FAILED_DEPENDENCY
 * @method static UNORDERED_COLLECTION
 * @method static UPGRADE_REQUIRED
 * @method static PRECONDITION_REQUIRED
 * @method static TOO_MANY_REQUESTS
 * @method static REQUEST_HEADER_FIELDS_TOO_LARGE
 * @method static UNAVAILABLE_FOR_LEGAL_REASONS
 * @method static INTERNAL_SERVER_ERROR
 * @method static NOT_IMPLEMENTED
 * @method static BAD_GATEWAY
 * @method static SERVICE_UNAVAILABLE
 * @method static GATEWAY_TIMEOUT
 * @method static VERSION_NOT_SUPPORTED
 * @method static VARIANT_ALSO_NEGOTIATES
 * @method static INSUFFICIENT_STORAGE
 * @method static LOOP_DETECTED
 * @method static NOT_EXTENDED
 * @method static NETWORK_AUTHENTICATION_REQUIRED
 */
final class HttpStatus extends Enum
{
    /**
     * Continue
     *
     * @var int
     */
    public const CONTINUE = 100;

    /**
     * Switching protocols
     *
     * @var int
     */
    public const SWITCHING_PROTOCOLS = 101;

    /**
     * Processing
     *
     * @var int
     */
    public const PROCESSING = 102;

    /**
     * OK
     *
     * @var int
     */
    public const OK = 200;

    /**
     * Created
     *
     * @var int
     */
    public const CREATED = 201;

    /**
     * Accepted
     *
     * @var int
     */
    public const ACCEPTED = 202;

    /**
     * Non authoritative information
     *
     * @var int
     */
    public const NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * No content
     *
     * @var int
     */
    public const NO_CONTENT = 204;

    /**
     * Reset content
     *
     * @var int
     */
    public const RESET_CONTENT = 205;

    /**
     * Partial content
     *
     * @var int
     */
    public const PARTIAL_CONTENT = 206;

    /**
     * Multi status
     *
     * @var int
     */
    public const MULTI_STATUS = 207;

    /**
     * Already reported
     *
     * @var int
     */
    public const ALREADY_REPORTED = 208;

    /**
     * IM used
     *
     * @var int
     */
    public const IM_USED = 226;

    /**
     * Multiple choices
     *
     * @var int
     */
    public const MULTIPLE_CHOICES = 300;

    /**
     * Moved permanently
     *
     * @var int
     */
    public const MOVED_PERMANENTLY = 301;

    /**
     * Found
     *
     * @var int
     */
    public const FOUND = 302;

    /**
     * See other
     *
     * @var int
     */
    public const SEE_OTHER = 303;

    /**
     * Not modified
     *
     * @var int
     */
    public const NOT_MODIFIED = 304;

    /**
     * Use proxy
     *
     * @var int
     */
    public const USE_PROXY = 305;

    /**
     * Reserved
     *
     * @var int
     */
    public const RESERVED = 306;

    /**
     * Temporary redirect
     *
     * @var int
     */
    public const TEMPORARY_REDIRECT = 307;

    /**
     * Permanent redirect
     *
     * @var int
     */
    public const PERMANENT_REDIRECT = 308;

    /**
     * Bad request
     *
     * @var int
     */
    public const BAD_REQUEST = 400;

    /**
     * Unauthorized
     *
     * @var int
     */
    public const UNAUTHORIZED = 401;

    /**
     * Payment required
     *
     * @var int
     */
    public const PAYMENT_REQUIRED = 402;

    /**
     * Forbidden
     *
     * @var int
     */
    public const FORBIDDEN = 403;

    /**
     * Not found
     *
     * @var int
     */
    public const NOT_FOUND = 404;

    /**
     * Method not allowed
     *
     * @var int
     */
    public const METHOD_NOT_ALLOWED = 405;

    /**
     * Not acceptable
     *
     * @var int
     */
    public const NOT_ACCEPTABLE = 406;

    /**
     * Proxy authentication required
     *
     * @var int
     */
    public const PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * Request timeout
     *
     * @var int
     */
    public const REQUEST_TIMEOUT = 408;

    /**
     * Conflict
     *
     * @var int
     */
    public const CONFLICT = 409;

    /**
     * Gone
     *
     * @var int
     */
    public const GONE = 410;

    /**
     * Length required
     *
     * @var int
     */
    public const LENGTH_REQUIRED = 411;

    /**
     * Precondition failed
     *
     * @var int
     */
    public const PRECONDITION_FAILED = 412;

    /**
     * Request entity too large
     *
     * @var int
     */
    public const REQUEST_ENTITY_TOO_LARGE = 413;

    /**
     * Request URI too long
     *
     * @var int
     */
    public const REQUEST_URI_TOO_LONG = 414;

    /**
     * Unsupported media type
     *
     * @var int
     */
    public const UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * Requested range not satisfiable
     *
     * @var int
     */
    public const REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    /**
     * Expectation failed
     *
     * @var int
     */
    public const EXPECTATION_FAILED = 417;

    /**
     * I'm a teapot
     *
     * @var int
     */
    public const I_AM_A_TEAPOT = 418;

    /**
     * Enhance your calm
     *
     * @var int
     */
    public const ENHANCE_YOUR_CALM = 420;

    /**
     * Misdirected request
     *
     * @var int
     */
    public const MISDIRECTED_REQUEST = 421;

    /**
     * Unprocessable entity
     *
     * @var int
     */
    public const UNPROCESSABLE_ENTITY = 422;

    /**
     * Locked
     *
     * @var int
     */
    public const LOCKED = 423;

    /**
     * Failed dependency
     *
     * @var int
     */
    public const FAILED_DEPENDENCY = 424;

    /**
     * Unordered collection
     *
     * @var int
     */
    public const UNORDERED_COLLECTION = 425;

    /**
     * Upgrade required
     *
     * @var int
     */
    public const UPGRADE_REQUIRED = 426;

    /**
     * Precondition required
     *
     * @var int
     */
    public const PRECONDITION_REQUIRED = 428;

    /**
     * Too many requests
     *
     * @var int
     */
    public const TOO_MANY_REQUESTS = 429;

    /**
     * Request header fields too large
     *
     * @var int
     */
    public const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    /**
     * Unavailable for legal reasons
     *
     * @var int
     */
    public const UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    /**
     * Internal server error
     *
     * @var int
     */
    public const INTERNAL_SERVER_ERROR = 500;

    /**
     * Not implemented
     *
     * @var int
     */
    public const NOT_IMPLEMENTED = 501;

    /**
     * Bad gateway
     *
     * @var int
     */
    public const BAD_GATEWAY = 502;

    /**
     * Service unavailable
     *
     * @var int
     */
    public const SERVICE_UNAVAILABLE = 503;

    /**
     * Gateway timeout
     *
     * @var int
     */
    public const GATEWAY_TIMEOUT = 504;

    /**
     * Version not supported
     *
     * @var int
     */
    public const VERSION_NOT_SUPPORTED = 505;

    /**
     * Variant also negotiates
     *
     * @var int
     */
    public const VARIANT_ALSO_NEGOTIATES = 506;

    /**
     * Insufficient storage
     *
     * @var int
     */
    public const INSUFFICIENT_STORAGE = 507;

    /**
     * Loop detected
     *
     * @var int
     */
    public const LOOP_DETECTED = 508;

    /**
     * Not extended
     *
     * @var int
     */
    public const NOT_EXTENDED = 510;

    /**
     * Network authentication required
     *
     * @var int
     */
    public const NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * Status texts
     *
     * @var array
     */
    protected static $statusTexts = [
        self::CONTINUE                        => 'Continue',
        self::SWITCHING_PROTOCOLS             => 'Switching Protocols',
        self::PROCESSING                      => 'Processing',
        self::OK                              => 'OK',
        self::CREATED                         => 'Created',
        self::ACCEPTED                        => 'Accepted',
        self::NON_AUTHORITATIVE_INFORMATION   => 'Non-Authoritative Information',
        self::NO_CONTENT                      => 'No Content',
        self::RESET_CONTENT                   => 'Reset Content',
        self::PARTIAL_CONTENT                 => 'Partial Content',
        self::MULTI_STATUS                    => 'Multi-Status',
        self::ALREADY_REPORTED                => 'Already Reported',
        self::IM_USED                         => 'IM Used',
        self::MULTIPLE_CHOICES                => 'Multiple Choices',
        self::MOVED_PERMANENTLY               => 'Moved Permanently',
        self::FOUND                           => 'Found',
        self::SEE_OTHER                       => 'See Other',
        self::NOT_MODIFIED                    => 'Not Modified',
        self::USE_PROXY                       => 'Use Proxy',
        self::RESERVED                        => 'Reserved',
        self::TEMPORARY_REDIRECT              => 'Temporary Redirect',
        self::PERMANENT_REDIRECT              => 'Permanent Redirect',
        self::BAD_REQUEST                     => 'Bad Request',
        self::UNAUTHORIZED                    => 'Unauthorized',
        self::PAYMENT_REQUIRED                => 'Payment Required',
        self::FORBIDDEN                       => 'Forbidden',
        self::NOT_FOUND                       => 'Not Found',
        self::METHOD_NOT_ALLOWED              => 'Method Not Allowed',
        self::NOT_ACCEPTABLE                  => 'Not Acceptable',
        self::PROXY_AUTHENTICATION_REQUIRED   => 'Proxy Authentication Required',
        self::REQUEST_TIMEOUT                 => 'Request Timeout',
        self::CONFLICT                        => 'Conflict',
        self::GONE                            => 'Gone',
        self::LENGTH_REQUIRED                 => 'Length Required',
        self::PRECONDITION_FAILED             => 'Precondition Failed',
        self::REQUEST_ENTITY_TOO_LARGE        => 'Payload Too Large',
        self::REQUEST_URI_TOO_LONG            => 'URI Too Long',
        self::UNSUPPORTED_MEDIA_TYPE          => 'Unsupported Media Type',
        self::REQUESTED_RANGE_NOT_SATISFIABLE => 'Range Not Satisfiable',
        self::EXPECTATION_FAILED              => 'Expectation Failed',
        self::I_AM_A_TEAPOT                   => "I'm a teapot",
        self::ENHANCE_YOUR_CALM               => 'Enhance Your Calm',
        self::MISDIRECTED_REQUEST             => 'Misdirected Request',
        self::UNPROCESSABLE_ENTITY            => 'Unprocessable Entity',
        self::LOCKED                          => 'Locked',
        self::FAILED_DEPENDENCY               => 'Failed Dependency',
        self::UNORDERED_COLLECTION            => 'Unordered Collection',
        self::UPGRADE_REQUIRED                => 'Upgrade Required',
        self::PRECONDITION_REQUIRED           => 'Precondition Required',
        self::TOO_MANY_REQUESTS               => 'Too Many Requests',
        self::REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
        self::UNAVAILABLE_FOR_LEGAL_REASONS   => 'Unavailable For Legal Reasons',
        self::INTERNAL_SERVER_ERROR           => 'Internal Server Error',
        self::NOT_IMPLEMENTED                 => 'Not Implemented',
        self::BAD_GATEWAY                     => 'Bad Gateway',
        self::SERVICE_UNAVAILABLE             => 'Service Unavailable',
        self::GATEWAY_TIMEOUT                 => 'Gateway Timeout',
        self::VERSION_NOT_SUPPORTED           => 'HTTP Version Not Supported',
        self::VARIANT_ALSO_NEGOTIATES         => 'Variant Also Negotiates',
        self::INSUFFICIENT_STORAGE            => 'Insufficient Storage',
        self::LOOP_DETECTED                   => 'Loop Detected',
        self::NOT_EXTENDED                    => 'Not Extended',
        self::NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required',
    ];

    /**
     * Retrieves the status text
     *
     * @return string
     */
    public function text(): string
    {
        return static::$statusTexts[$this->value()];
    }
}
