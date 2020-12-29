<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\Identifier;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Assert;
use Novuso\System\Utility\VarPrinter;

/**
 * Class Uri
 *
 * @link http://tools.ietf.org/html/rfc3986 RFC 3986
 */
class Uri extends ValueObject implements Comparable
{
    /**
     * URI capture pattern
     *
     * This is a variation on the capture pattern in RFC 3986 - appendix B.
     *
     * @link http://tools.ietf.org/html/rfc3986#appendix-B
     */
    protected const URI_PATTERN = '/\A(?:([^:\/?#]+)(:))?(?:(\/\/)([^\/?#]*))?([^?#]*)(?:(\?)([^#]*))?(?:(#)(.*))?\z/';

    /**
     * Authority capture pattern
     *
     * This pattern is used to capture authority sub-components.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.2
     */
    protected const AUTHORITY_PATTERN = '/\A(?:([^@]*)@)?(\[[^\]]*\]|[^:]*)(?::(\d*))?\z/';

    /**
     * Scheme validation pattern
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.1
     */
    protected const SCHEME_PATTERN = '/\A[a-z][a-z0-9+.\-]*\z/i';

    /**
     * Percent encoded characters
     *
     * @link http://tools.ietf.org/html/rfc3986#section-2.1
     */
    protected const PCT_ENCODED_SET = '%[a-fA-F0-9]{2}';

    /**
     * Sub-component delimiters
     *
     * @link http://tools.ietf.org/html/rfc3986#section-2.2
     */
    protected const SUB_DELIMS_SET = '!$&\'()*+,;=';

    /**
     * Set of unreserved characters
     *
     * @link http://tools.ietf.org/html/rfc3986#section-2.3
     */
    protected const UNRESERVED_SET = 'a-zA-Z0-9\-._~';

    protected static array $defaultPorts = [];

    protected string $scheme;
    protected ?string $authority;
    protected string $path;
    protected ?string $query;
    protected ?string $fragment;
    protected ?string $userInfo;
    protected ?string $host;
    protected ?int $port;

    /**
     * Constructs Uri
     *
     * @internal
     *
     * @throws DomainException When values are not valid
     */
    protected function __construct(
        string $path,
        ?string $scheme = null,
        ?string $authority = null,
        ?string $query = null,
        ?string $fragment = null
    ) {
        $auth = static::parseAuthority($authority);
        $this->userInfo = static::normalizeUserInfo($auth['userInfo']);
        $this->host = static::normalizeHost($auth['host']);
        $this->port = static::normalizePort($auth['port'], $scheme);
        $this->authority = static::buildAuthority($this->userInfo, $this->host, $this->port);
        $this->scheme = static::normalizeScheme($scheme);
        $this->path = static::normalizePath($path);
        $this->query = static::normalizeQuery($query);
        $this->fragment = static::normalizeFragment($fragment);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        return static::parse($value);
    }

    /**
     * Creates instance from a URI string
     *
     * @throws DomainException When the URI is not valid
     */
    public static function parse(string $uri): static
    {
        preg_match(static::URI_PATTERN, $uri, $matches);

        $components = static::componentsFromMatches($matches);
        $scheme = $components['scheme'];
        $authority = $components['authority'];
        $path = $components['path'];
        $query = $components['query'];
        $fragment = $components['fragment'];

        return new static($path, $scheme, $authority, $query, $fragment);
    }

    /**
     * Creates instance from a base URI and relative reference
     *
     * @link http://tools.ietf.org/html/rfc3986#section-5.2
     *
     * @throws DomainException When the base or reference are invalid
     */
    public static function resolve(Uri|string $base, string $reference, bool $strict = true): static
    {
        if (!($base instanceof self)) {
            $base = static::parse($base);
        }

        preg_match(static::URI_PATTERN, $reference, $matches);
        $ref = static::componentsFromMatches($matches);

        // http://tools.ietf.org/html/rfc3986#section-5.2.2
        // A non-strict parser may ignore a scheme in the reference if it is
        // identical to the base URI's scheme
        if (!$strict
            && ($ref['scheme'] !== null && $base->scheme() === $ref['scheme'])) {
            $ref['scheme'] = null;
        }

        if ($ref['scheme'] !== null) {
            $scheme = $ref['scheme'];
            $authority = $ref['authority'];
            $path = static::removeDotSegments($ref['path']);
            $query = $ref['query'];
        } else {
            // http://tools.ietf.org/html/rfc3986#section-3.3
            // In addition, a URI reference (Section 4.1) may be a
            // relative-path reference, in which case the first path segment
            // cannot contain a colon (":") character.
            // START: extra check for colon in first segment
            $segments = explode('/', trim($ref['path'], '/'));
            if (isset($segments[0]) && str_contains($segments[0], ':')) {
                $message = sprintf(
                    'First segment in reference (%s) cannot contain a colon (":")',
                    $reference
                );
                throw new DomainException($message);
            }
            // END: extra check for colon in first segment
            if ($ref['authority'] !== null) {
                $authority = $ref['authority'];
                $path = static::removeDotSegments($ref['path']);
                $query = $ref['query'];
            } else {
                if ($ref['path'] === '') {
                    $path = $base->path();
                    if ($ref['query'] !== null) {
                        $query = $ref['query'];
                    } else {
                        $query = $base->query();
                    }
                } else {
                    if ($ref['path'][0] === '/') {
                        $path = static::removeDotSegments($ref['path']);
                    } else {
                        $path = static::mergePaths($base, $ref['path']);
                        $path = static::removeDotSegments($path);
                    }
                    $query = $ref['query'];
                }
                $authority = $base->authority();
            }
            $scheme = $base->scheme();
        }
        $fragment = $ref['fragment'];

        return new static($path, $scheme, $authority, $query, $fragment);
    }

    /**
     * Creates instance from components
     *
     * The following key names should hold their respective values:
     *
     * * scheme
     * * authority
     * * path
     * * query
     * * fragment
     *
     * @throws DomainException When values are not valid
     */
    public static function fromArray(array $components): static
    {
        $scheme = $components['scheme'] ?? null;
        $authority = $components['authority'] ?? null;
        $path = $components['path'] ?? '';
        $query = $components['query'] ?? null;
        $fragment = $components['fragment'] ?? null;

        return new static($path, $scheme, $authority, $query, $fragment);
    }

    /**
     * Creates instance with a given scheme
     *
     * @throws DomainException When scheme is not valid
     */
    public function withScheme(string $scheme): static
    {
        return new static(
            $this->path(),
            $scheme,
            $this->authority(),
            $this->query(),
            $this->fragment()
        );
    }

    /**
     * Creates instance with a given authority
     *
     * @throws DomainException When authority is not valid
     */
    public function withAuthority(?string $authority): static
    {
        return new static(
            $this->path(),
            $this->scheme(),
            $authority,
            $this->query(),
            $this->fragment()
        );
    }

    /**
     * Creates instance with a given path
     *
     * @throws DomainException When path is not valid
     */
    public function withPath(string $path): static
    {
        return new static(
            $path,
            $this->scheme(),
            $this->authority(),
            $this->query(),
            $this->fragment()
        );
    }

    /**
     * Creates instance with a given query
     *
     * @throws DomainException When query is not valid
     */
    public function withQuery(?string $query): static
    {
        return new static(
            $this->path(),
            $this->scheme(),
            $this->authority(),
            $query,
            $this->fragment()
        );
    }

    /**
     * Creates instance with a given fragment
     *
     * @throws DomainException When fragment is not valid
     */
    public function withFragment(?string $fragment): static
    {
        return new static(
            $this->path(),
            $this->scheme(),
            $this->authority(),
            $this->query(),
            $fragment
        );
    }

    /**
     * Retrieves the scheme
     */
    public function scheme(): string
    {
        return $this->scheme;
    }

    /**
     * Retrieves the authority
     */
    public function authority(): ?string
    {
        return $this->authority;
    }

    /**
     * Retrieves the path
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Retrieves the query
     */
    public function query(): ?string
    {
        return $this->query;
    }

    /**
     * Retrieves the fragment
     */
    public function fragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * Retrieves the user info
     */
    public function userInfo(): ?string
    {
        return $this->userInfo;
    }

    /**
     * Retrieves the host
     */
    public function host(): ?string
    {
        return $this->host;
    }

    /**
     * Retrieves the port
     */
    public function port(): ?int
    {
        return $this->port;
    }

    /**
     * Retrieves an array representation
     */
    public function toArray(): array
    {
        return [
            'scheme'    => $this->scheme,
            'authority' => $this->authority,
            'path'      => $this->path,
            'query'     => $this->query,
            'fragment'  => $this->fragment
        ];
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        $output = sprintf('%s:', $this->scheme);

        if ($this->authority !== null) {
            $output .= sprintf('//%s', $this->authority);
        }

        $output .= $this->path;

        if ($this->query !== null) {
            $output .= sprintf('?%s', $this->query);
        }

        if ($this->fragment !== null) {
            $output .= sprintf('#%s', $this->fragment);
        }

        return $output;
    }

    /**
     * Retrieves string representation without user info
     */
    public function display(): string
    {
        $output = sprintf('%s:', $this->scheme);

        if ($this->authority !== null) {
            $output .= sprintf('//%s', $this->host);
            if ($this->port !== null) {
                $output .= sprintf(':%d', $this->port);
            }
        }

        $output .= $this->path;

        if ($this->query !== null) {
            $output .= sprintf('?%s', $this->query);
        }

        if ($this->fragment !== null) {
            $output .= sprintf('#%s', $this->fragment);
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function compareTo(mixed $object): int
    {
        if ($this === $object) {
            return 0;
        }

        Assert::areSameType($this, $object);

        $strComp = strnatcmp($this->toString(), $object->toString());

        return $strComp <=> 0;
    }

    /**
     * Exchanges URI_PATTERN matches for components
     */
    protected static function componentsFromMatches(array $matches): array
    {
        // http://tools.ietf.org/html/rfc3986#section-5.3
        // Note that we are careful to preserve the distinction between a
        // component that is undefined, meaning that its separator was not
        // present in the reference, and a component that is empty, meaning
        // that the separator was present and was immediately followed by the
        // next component separator or the end of the reference.
        if (isset($matches[2]) && $matches[2]) {
            $scheme = $matches[1] ?? '';
        } else {
            $scheme = null;
        }
        if (isset($matches[3]) && $matches[3]) {
            $authority = $matches[4] ?? '';
        } else {
            $authority = null;
        }
        $path = $matches[5] ?? '';
        if (isset($matches[6]) && $matches[6]) {
            $query = $matches[7] ?? '';
        } else {
            $query = null;
        }
        if (isset($matches[8]) && $matches[8]) {
            $fragment = $matches[9] ?? '';
        } else {
            $fragment = null;
        }

        return [
            'scheme'    => $scheme,
            'authority' => $authority,
            'path'      => $path,
            'query'     => $query,
            'fragment'  => $fragment
        ];
    }

    /**
     * Parses authority component into parts
     */
    protected static function parseAuthority(?string $authority): array
    {
        if ($authority === null) {
            return [
                'userInfo' => null,
                'host'     => null,
                'port'     => null
            ];
        }

        preg_match(static::AUTHORITY_PATTERN, $authority, $matches);

        $userInfo = isset($matches[1]) && $matches[1] ? $matches[1] : null;
        $host = isset($matches[2]) && $matches[2] ? $matches[2] : '';
        $port = isset($matches[3]) && $matches[3] ? ((int) $matches[3]) : null;

        return [
            'userInfo' => $userInfo,
            'host'     => $host,
            'port'     => $port
        ];
    }

    /**
     * Builds authority from parts
     */
    protected static function buildAuthority(?string $userInfo, ?string $host, ?int $port): ?string
    {
        if ($host === null) {
            return null;
        }

        $authority = '';

        if ($userInfo !== null) {
            $authority .= sprintf('%s@', $userInfo);
        }
        $authority .= $host;
        if ($port !== null) {
            $authority .= sprintf(':%d', $port);
        }

        return $authority;
    }

    /**
     * Validates and normalizes the scheme
     *
     * @throws DomainException When the scheme is invalid
     */
    protected static function normalizeScheme(?string $scheme): string
    {
        if (!static::isValidScheme($scheme)) {
            $message = sprintf(
                'Invalid URI scheme: %s',
                VarPrinter::toString($scheme)
            );
            throw new DomainException($message);
        }

        return strtolower($scheme);
    }

    /**
     * Validates and normalizes the path
     *
     * @throws DomainException When the path is invalid
     */
    protected static function normalizePath(string $path): string
    {
        if (!static::isValidPath($path)) {
            $message = sprintf('Invalid URI path: %s', $path);
            throw new DomainException($message);
        }

        $path = static::removeDotSegments($path);

        return static::encodePath(static::decode(
            $path,
            static::UNRESERVED_SET
        ));
    }

    /**
     * Validates and normalizes the query
     *
     * @throws DomainException When the query is invalid
     */
    protected static function normalizeQuery(?string $query): ?string
    {
        if ($query === null) {
            return null;
        }

        if (!static::isValidQuery($query)) {
            $message = sprintf('Invalid URI query: %s', $query);
            throw new DomainException($message);
        }

        return static::encodeQuery(static::decode(
            $query,
            static::UNRESERVED_SET
        ));
    }

    /**
     * Validates and normalizes the fragment
     *
     * @throws DomainException When the fragment is invalid
     */
    protected static function normalizeFragment(?string $fragment): ?string
    {
        if ($fragment === null) {
            return null;
        }

        if (!static::isValidFragment($fragment)) {
            $message = sprintf('Invalid URI fragment: %s', $fragment);
            throw new DomainException($message);
        }

        return static::encodeFragment(static::decode(
            $fragment,
            static::UNRESERVED_SET
        ));
    }

    /**
     * Validates and normalizes the user info
     *
     * @throws DomainException When the user info is invalid
     */
    protected static function normalizeUserInfo(?string $userInfo): ?string
    {
        if ($userInfo === null) {
            return null;
        }

        if (!static::isValidUserInfo($userInfo)) {
            $message = sprintf('Invalid user info: %s', $userInfo);
            throw new DomainException($message);
        }

        return static::encodeUserInfo(static::decode(
            $userInfo,
            static::UNRESERVED_SET
        ));
    }

    /**
     * Validates and normalizes the host
     *
     * @throws DomainException When the host is invalid
     */
    protected static function normalizeHost(?string $host): ?string
    {
        if ($host === null) {
            return null;
        }

        if ($host === '') {
            return '';
        }

        if (!static::isValidHost($host)) {
            $message = sprintf('Invalid host: %s', $host);
            throw new DomainException($message);
        }

        // Although host is case-insensitive, producers and normalizers should
        // use lowercase for registered names and hexadecimal addresses for the
        // sake of uniformity, while only using uppercase letters for
        // percent-encodings.
        $host = mb_strtolower($host, 'UTF-8');

        return static::encodeHost(static::decode(
            $host,
            static::UNRESERVED_SET
        ));
    }

    /**
     * Validates and normalizes the port
     */
    protected static function normalizePort(?int $port, ?string $scheme): ?int
    {
        if ($port === null) {
            return null;
        }

        if ($scheme
            && isset(static::$defaultPorts[$scheme])
            && ($port == static::$defaultPorts[$scheme])) {
            return null;
        }

        return $port;
    }

    /**
     * Encodes the path
     */
    protected static function encodePath(string $path): string
    {
        // http://tools.ietf.org/html/rfc3986#section-3.3
        // path          = path-abempty    ; begins with "/" or is empty
        //               / path-absolute   ; begins with "/" but not "//"
        //               / path-noscheme   ; begins with a non-colon segment
        //               / path-rootless   ; begins with a segment
        //               / path-empty      ; zero characters
        //
        // path-abempty  = *( "/" segment )
        // path-absolute = "/" [ segment-nz *( "/" segment ) ]
        // path-noscheme = segment-nz-nc *( "/" segment )
        // path-rootless = segment-nz *( "/" segment )
        // path-empty    = 0<pchar>
        // segment       = *pchar
        // segment-nz    = 1*pchar
        // segment-nz-nc = 1*( unreserved / pct-encoded / sub-delims / "@" )
        //               ; non-zero-length segment without any colon ":"
        // pchar         = unreserved / pct-encoded / sub-delims / ":" / "@"
        $excluded = static::UNRESERVED_SET.static::SUB_DELIMS_SET.':@\/';

        return static::encode($path, $excluded);
    }

    /**
     * Encodes the query
     */
    protected static function encodeQuery(string $query): string
    {
        // http://tools.ietf.org/html/rfc3986#section-3.4
        // query = *( pchar / "/" / "?" )
        // pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
        $excluded = static::UNRESERVED_SET.static::SUB_DELIMS_SET.':@\/\?';

        return static::encode($query, $excluded);
    }

    /**
     * Encodes the fragment
     */
    protected static function encodeFragment(string $fragment): string
    {
        // http://tools.ietf.org/html/rfc3986#section-3.5
        // fragment = *( pchar / "/" / "?" )
        // pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
        $excluded = static::UNRESERVED_SET.static::SUB_DELIMS_SET.':@\/\?';

        return static::encode($fragment, $excluded);
    }

    /**
     * Encodes the user info
     */
    protected static function encodeUserInfo(string $userInfo): string
    {
        // http://tools.ietf.org/html/rfc3986#section-3.2.1
        // userinfo = *( unreserved / pct-encoded / sub-delims / ":" )
        $excluded = static::UNRESERVED_SET.static::SUB_DELIMS_SET.':';

        return static::encode($userInfo, $excluded);
    }

    /**
     * Encodes the host
     */
    protected static function encodeHost(string $host): string
    {
        // http://tools.ietf.org/html/rfc3986#section-3.2.2
        // IP-literal = "[" ( IPv6address / IPvFuture  ) "]"
        // IPvFuture  = "v" 1*HEXDIG "." 1*( unreserved / sub-delims / ":" )
        if ($host[0] === '[') {
            $excluded = static::UNRESERVED_SET.static::SUB_DELIMS_SET.'\[\]:';

            return static::encode($host, $excluded);
        }

        // http://tools.ietf.org/html/rfc3986#section-3.2.2
        // NOTE: characters in IPv4 address are all in the unreserved set
        // IPv4address = dec-octet "." dec-octet "." dec-octet "." dec-octet
        // reg-name    = *( unreserved / pct-encoded / sub-delims )
        $excluded = static::UNRESERVED_SET.static::SUB_DELIMS_SET;

        return static::encode($host, $excluded);
    }

    /**
     * Encodes a component
     *
     * @codeCoverageIgnore
     */
    protected static function encode(string $component, string $excluded): string
    {
        return preg_replace_callback(
            static::encodingRegex($excluded),
            function (array $matches) {
                return rawurlencode($matches[0]);
            },
            $component
        );
    }

    /**
     * Decodes a component
     */
    protected static function decode(string $component, string $allowed): string
    {
        $allowed = sprintf('/[%s]/', $allowed);
        $encoded = sprintf('/%s/', static::PCT_ENCODED_SET);

        return preg_replace_callback(
            $encoded,
            function ($matches) use ($allowed) {
                $char = rawurldecode($matches[0]);

                if (preg_match($allowed, $char)) {
                    return $char;
                }

                return strtoupper($matches[0]);
            },
            $component
        );
    }

    /**
     * Removes dot segments
     *
     * Algorithm based on section 5.2.4 of RFC 3986.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-5.2.4
     */
    protected static function removeDotSegments(string $path): string
    {
        $output = '';
        while ($path) {
            if ('..' == $path || '.' == $path) {
                break;
            }
            switch (true) {
                case ('/./' == substr($path, 0, 3)):
                case ('./' == substr($path, 0, 2)):
                    $path = substr($path, 2);
                    break;
                case ('../' == substr($path, 0, 3)):
                    $path = substr($path, 3);
                    break;
                case ('/../' == substr($path, 0, 4)):
                    $path = '/'.substr($path, 4);
                    $pos = strrpos($output, '/', -1);
                    if ($pos !== false) {
                        $output = substr($output, 0, $pos);
                    }
                    break;
                case ('/..' == substr($path, 0, 3)
                    && (in_array(substr($path, 3, 1), [false, '', '/'], true))):
                    $path = '/'.substr($path, 3);
                    $pos = strrpos($output, '/', -1);
                    if ($pos !== false) {
                        $output = substr($output, 0, $pos);
                    }
                    break;
                case ('/.' == substr($path, 0, 2)
                    && (in_array(substr($path, 2, 1), [false, '', '/'], true))):
                    $path = '/'.substr($path, 2);
                    break;
                default:
                    $nextSlash = strpos($path, '/', 1);
                    if (false === $nextSlash) {
                        $segment = $path;
                    } else {
                        $segment = substr($path, 0, $nextSlash);
                    }
                    $output .= $segment;
                    $path = substr($path, strlen($segment));
                    break;
            }
        }

        return $output;
    }

    /**
     * Merges a base URI and relative path
     *
     * @link http://tools.ietf.org/html/rfc3986#section-5.2.3
     */
    protected static function mergePaths(Uri $baseUri, string $relative): string
    {
        $basePath = $baseUri->path();

        if ($baseUri->authority() !== null && $basePath === '') {
            return sprintf('/%s', $relative);
        }

        $last = strrpos($basePath, '/');

        if ($last !== false) {
            return sprintf('%s/%s', substr($basePath, 0, $last), $relative);
        }

        return $relative;
    }

    /**
     * Checks if a scheme is valid
     */
    protected static function isValidScheme(?string $scheme): bool
    {
        // http://tools.ietf.org/html/rfc3986#section-3
        // The scheme and path components are required, though the path may be
        // empty (no characters)
        if ($scheme === null || $scheme === '') {
            return false;
        }

        return !!preg_match(static::SCHEME_PATTERN, $scheme);
    }

    /**
     * Checks if a path is valid
     */
    protected static function isValidPath(string $path): bool
    {
        // http://tools.ietf.org/html/rfc3986#section-3
        // The scheme and path components are required, though the path may be
        // empty (no characters)
        if ($path === '') {
            return true;
        }

        // http://tools.ietf.org/html/rfc3986#section-3.3
        // path          = path-abempty    ; begins with "/" or is empty
        //               / path-absolute   ; begins with "/" but not "//"
        //               / path-noscheme   ; begins with a non-colon segment
        //               / path-rootless   ; begins with a segment
        //               / path-empty      ; zero characters
        //
        // path-abempty  = *( "/" segment )
        // path-absolute = "/" [ segment-nz *( "/" segment ) ]
        // path-noscheme = segment-nz-nc *( "/" segment )
        // path-rootless = segment-nz *( "/" segment )
        // path-empty    = 0<pchar>
        // segment       = *pchar
        // segment-nz    = 1*pchar
        // segment-nz-nc = 1*( unreserved / pct-encoded / sub-delims / "@" )
        //               ; non-zero-length segment without any colon ":"
        // pchar         = unreserved / pct-encoded / sub-delims / ":" / "@"
        $pattern = sprintf(
            '/\A(?:(?:[%s%s:@]|(?:%s))*\/?)*\z/',
            static::UNRESERVED_SET,
            static::SUB_DELIMS_SET,
            static::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $path);
    }

    /**
     * Checks if a query is valid
     */
    protected static function isValidQuery(?string $query): bool
    {
        if ($query === null || $query === '') {
            return true;
        }

        // http://tools.ietf.org/html/rfc3986#section-3.4
        // query = *( pchar / "/" / "?" )
        // pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
        $pattern = sprintf(
            '/\A(?:[%s%s\/?:@]|(?:%s))*\z/',
            static::UNRESERVED_SET,
            static::SUB_DELIMS_SET,
            static::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $query);
    }

    /**
     * Checks if a fragment is valid
     */
    protected static function isValidFragment(?string $fragment): bool
    {
        if ($fragment === null || $fragment === '') {
            return true;
        }

        // http://tools.ietf.org/html/rfc3986#section-3.5
        // fragment = *( pchar / "/" / "?" )
        // pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
        $pattern = sprintf(
            '/\A(?:[%s%s\/?:@]|(?:%s))*\z/',
            static::UNRESERVED_SET,
            static::SUB_DELIMS_SET,
            static::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $fragment);
    }

    /**
     * Checks if user info is valid
     */
    protected static function isValidUserInfo(string $userInfo): bool
    {
        // http://tools.ietf.org/html/rfc3986#section-3.2.1
        // userinfo = *( unreserved / pct-encoded / sub-delims / ":" )
        $pattern = sprintf(
            '/\A(?:[%s%s:]|(?:%s))*\z/',
            static::UNRESERVED_SET,
            static::SUB_DELIMS_SET,
            static::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $userInfo);
    }

    /**
     * Checks if a host is valid
     */
    protected static function isValidHost(string $host): bool
    {
        // http://tools.ietf.org/html/rfc3986#section-3.2.2
        // A host identified by an Internet Protocol literal address, version 6
        // [RFC3513] or later, is distinguished by enclosing the IP literal
        // within square brackets ("[" and "]").  This is the only place where
        // square bracket characters are allowed in the URI syntax.
        if (str_contains($host, '[')) {
            return static::isValidIpLiteral($host);
        }

        // IPv4address = dec-octet "." dec-octet "." dec-octet "." dec-octet
        $dec = '(?:(?:2[0-4]|1[0-9]|[1-9])?[0-9]|25[0-5])';
        $ipV4 = sprintf('/\A(?:%s\.){3}%s\z/', $dec, $dec);
        if (preg_match($ipV4, $host)) {
            return true;
        }

        // reg-name = *( unreserved / pct-encoded / sub-delims )
        $pattern = sprintf(
            '/\A(?:[%s%s]|(?:%s))*\z/',
            static::UNRESERVED_SET,
            static::SUB_DELIMS_SET,
            static::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $host);
    }

    /**
     * Checks if a IP literal is valid
     */
    protected static function isValidIpLiteral(string $ip): bool
    {
        // outer brackets
        $length = strlen($ip);
        if ($ip[0] !== '[' || $ip[$length - 1] !== ']') {
            return false;
        }

        // remove brackets
        $ip = substr($ip, 1, $length - 2);

        // starts with "v" (case-insensitive)
        // IPvFuture = "v" 1*HEXDIG "." 1*( unreserved / sub-delims / ":" )
        $pattern = sprintf(
            '/\A[v](?:[a-f0-9]+)\.[%s%s:]+\z/i',
            static::UNRESERVED_SET,
            static::SUB_DELIMS_SET
        );
        if (preg_match($pattern, $ip)) {
            return true;
        }

        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Provides the encoding regex to prevent double encoding
     */
    protected static function encodingRegex(string $excluded): string
    {
        return sprintf('/(?:[^%s%%]+|%%(?![a-fA-F0-9]{2}))/', $excluded);
    }
}
