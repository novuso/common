<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\Identifier;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Assert;
use Novuso\System\Utility\Validate;

/**
 * Class Uuid
 *
 * @link http://tools.ietf.org/html/rfc4122 RFC 4122
 */
final class Uuid extends ValueObject implements Comparable
{
    public const VARIANT_RESERVED_NCS = 0;
    public const VARIANT_RFC_4122 = 2;
    public const VARIANT_RESERVED_MICROSOFT = 6;
    public const VARIANT_RESERVED_FUTURE = 7;
    public const VERSION_TIME = 1;
    public const VERSION_DCE = 2;
    public const VERSION_MD5 = 3;
    public const VERSION_RANDOM = 4;
    public const VERSION_SHA1 = 5;
    public const VERSION_UNKNOWN = 0;
    public const NAMESPACE_DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_OID = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_X500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';
    public const NIL = '00000000-0000-0000-0000-000000000000';

    protected const UUID = '/\A([a-f0-9]{8})-([a-f0-9]{4})-([a-f0-9]{4})-([a-f0-9]{2})([a-f0-9]{2})-([a-f0-9]{12})\z/';
    protected const UUID_HEX = '/\A([a-f0-9]{8})([a-f0-9]{4})([a-f0-9]{4})([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{12})\z/';

    /**
     * Constructs Uuid
     *
     * @internal
     *
     * @link https://tools.ietf.org/html/rfc4122#section-4.1.2 Layout
     */
    protected function __construct(
        protected string $timeLow,
        protected string $timeMid,
        protected string $timeHiAndVersion,
        protected string $clockSeqHiAndReserved,
        protected string $clockSeqLow,
        protected string $node
    )
    {
    }

    /**
     * Creates a pseudo-random instance
     */
    public static function random(): static
    {
        return static::uuid4();
    }

    /**
     * Creates a sequential pseudo-random instance
     *
     * This variation is not covered by RFC 4122, but it should provide
     * performance increases when using UUIDs as primary keys. The timestamp
     * should cover most significant bits or least significant bits depending
     * on how the database orders GUID values.
     *
     * @param bool $msb Whether or not timestamp covers most significant bits
     */
    public static function comb(bool $msb = true): static
    {
        $hash = bin2hex(random_bytes(10));
        $time = explode(' ', microtime());
        $milliseconds = sprintf('%d%03d', $time[1], $time[0] * 1000);
        $timestamp = sprintf('%012x', $milliseconds);
        if ($msb) {
            $hex = $timestamp.$hash;
        } else {
            $hex = $hash.$timestamp;
        }

        return static::fromUnformatted($hex, static::VERSION_RANDOM);
    }

    /**
     * Creates a time-based instance
     *
     * If node, clock sequence, or timestamp values are not passed in, they
     * will be generated.
     *
     * Generating the node and clock sequence are not recommended for this UUID
     * version. The current timestamp can be retrieved in the correct format
     * from the `Uuid::timestamp()` static method.
     *
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.5
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.6
     *
     * @param string|null $node      The node ID or MAC address
     * @param int|null    $clockSeq  The clock sequence as a 14-bit integer
     * @param string|null $timestamp The timestamp
     *
     * @throws DomainException When clockSeq is not 14-bit unsigned
     * @throws DomainException When the node is not 6 hex octets
     * @throws DomainException When timestamp is not 8 hex octets
     */
    public static function time(?string $node = null, ?int $clockSeq = null, ?string $timestamp = null): static
    {
        return static::uuid1($node, $clockSeq, $timestamp);
    }

    /**
     * Creates a named instance
     *
     * @throws DomainException When the namespace is not a valid UUID
     */
    public static function named(Uuid|string $namespace, string $name): static
    {
        return static::uuid5($namespace, $name);
    }

    /**
     * Creates an md5 named instance
     *
     * @throws DomainException When the namespace is not a valid UUID
     */
    public static function md5(Uuid|string $namespace, string $name): static
    {
        return static::uuid3($namespace, $name);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        return static::parse($value);
    }

    /**
     * Creates instance from a UUID string
     *
     * @throws DomainException When the string is not a valid UUID
     */
    public static function parse(string $uuid): static
    {
        $clean = strtolower(str_replace(
            ['urn:', 'uuid:', '{', '}'],
            '',
            $uuid
        ));

        if (!preg_match(static::UUID, $clean, $matches)) {
            $message = sprintf('Invalid UUID string: %s', $uuid);
            throw new DomainException($message);
        }

        $timeLow = $matches[1];
        $timeMid = $matches[2];
        $timeHiAndVersion = $matches[3];
        $clockSeqHiAndReserved = $matches[4];
        $clockSeqLow = $matches[5];
        $node = $matches[6];

        return new static(
            $timeLow,
            $timeMid,
            $timeHiAndVersion,
            $clockSeqHiAndReserved,
            $clockSeqLow,
            $node
        );
    }

    /**
     * Creates instance from a hexadecimal string
     *
     * @throws DomainException When the hex string is not valid
     */
    public static function fromHex(string $hex): static
    {
        $clean = strtolower($hex);

        if (!preg_match(static::UUID_HEX, $clean, $matches)) {
            $message = sprintf('Invalid UUID hex: %s', $hex);
            throw new DomainException($message);
        }

        $timeLow = $matches[1];
        $timeMid = $matches[2];
        $timeHiAndVersion = $matches[3];
        $clockSeqHiAndReserved = $matches[4];
        $clockSeqLow = $matches[5];
        $node = $matches[6];

        return new static(
            $timeLow,
            $timeMid,
            $timeHiAndVersion,
            $clockSeqHiAndReserved,
            $clockSeqLow,
            $node
        );
    }

    /**
     * Creates instance from a byte string
     *
     * @throws DomainException When the bytes string is not valid
     */
    public static function fromBytes(string $bytes): static
    {
        if (strlen($bytes) !== 16) {
            $message = sprintf(
                '%s expects $bytes to be a 16-bytes string',
                __METHOD__
            );
            throw new DomainException($message);
        }

        $steps = [];
        foreach (range(0, 15) as $i) {
            $steps[] = sprintf('%02x', ord($bytes[$i]));
            if (in_array($i, [3, 5, 7, 9])) {
                $steps[] = '-';
            }
        }

        return static::parse(implode('', $steps));
    }

    /**
     * Retrieves the timestamp
     *
     * The current time is defined as a 60-bit count of 100-nanosecond
     * intervals since 00:00:00.00, 15 October 1582.
     *
     * Return value is formatted as a hexadecimal string with time_low,
     * time_mid, and time_hi fields appearing in most significant bit order.
     *
     * @link http://tools.ietf.org/html/rfc4122#section-4.2.1
     */
    public static function timestamp(): string
    {
        // 122192928000000000 is the number of 100-nanosecond intervals between
        // the UUID epoch 1582-10-15 00:00:00 and the Unix epoch 1970-01-01 00:00:00
        $offset = 122192928000000000;
        $timeOfDay = gettimeofday();

        $time = ($timeOfDay['sec'] * 10000000) + ($timeOfDay['usec'] * 10) + $offset;
        $hi = intval($time / 0xffffffff);

        $timestamp = [];
        $timestamp[] = sprintf('%04x', (($hi >> 16) & 0xfff));
        $timestamp[] = sprintf('%04x', $hi & 0xffff);
        $timestamp[] = sprintf('%08x', $time & 0xffffffff);

        return implode('', $timestamp);
    }

    /**
     * Checks if a UUID string matches the correct layout
     */
    public static function isValid(string $uuid): bool
    {
        $uuid = strtolower(str_replace(['urn:', 'uuid:', '{', '}'], '', $uuid));

        if (!preg_match(static::UUID, $uuid)) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves the time_low field
     */
    public function timeLow(): string
    {
        return $this->timeLow;
    }

    /**
     * Retrieves the time_mid field
     */
    public function timeMid(): string
    {
        return $this->timeMid;
    }

    /**
     * Retrieves the time_hi_and_version field
     */
    public function timeHiAndVersion(): string
    {
        return $this->timeHiAndVersion;
    }

    /**
     * Retrieves the clock_seq_hi_and_reserved field
     */
    public function clockSeqHiAndReserved(): string
    {
        return $this->clockSeqHiAndReserved;
    }

    /**
     * Retrieves the clock_seq_low field
     */
    public function clockSeqLow(): string
    {
        return $this->clockSeqLow;
    }

    /**
     * Retrieves the node field
     */
    public function node(): string
    {
        return $this->node;
    }

    /**
     * Retrieves the most significant 64 bits in hexadecimal
     */
    public function mostSignificantBits(): string
    {
        return sprintf(
            '%s%s%s',
            $this->timeLow,
            $this->timeMid,
            $this->timeHiAndVersion
        );
    }

    /**
     * Retrieves the least significant 64 bits in hexadecimal
     */
    public function leastSignificantBits(): string
    {
        return sprintf(
            '%s%s%s',
            $this->clockSeqHiAndReserved,
            $this->clockSeqLow,
            $this->node
        );
    }

    /**
     * Retrieves the UUID version
     *
     * The version number is in the most significant 4 bits of the time stamp
     * as specified in section 4.1.3 of RFC 4122. If the version bits do not
     * match a known version, this method returns 0.
     *
     * The return value can be one of the following:
     *
     * * 1 - The time-based UUID version
     * * 2 - The DCE Security UUID version
     * * 3 - The name-based md5 hash UUID version
     * * 4 - The pseudo-randomly generated UUID version
     * * 5 - The name-based sha1 hash UUID version
     * * 0 - None of the above
     *
     * The Nil UUID (section 4.1.7) is an example of a UUID that does not match
     * a defined version number.
     */
    public function version(): int
    {
        $versions = [
            static::VERSION_TIME,
            static::VERSION_DCE,
            static::VERSION_MD5,
            static::VERSION_RANDOM,
            static::VERSION_SHA1
        ];

        $version = (int) hexdec(substr($this->timeHiAndVersion, 0, 1));

        if (in_array($version, $versions, true)) {
            return $version;
        }

        return static::VERSION_UNKNOWN;
    }

    /**
     * Retrieves the UUID variant
     *
     * The variant field determines the layout of the UUID as specified in
     * section 4.1.1 of RFC 4122. The variant consists of a variable number
     * of the most significant bits of octet 8 of the UUID.
     *
     * The return value can be one of the following:
     *
     * * 0 - Reserved, NCS backward compatibility
     * * 2 - RFC 4122; supported by this class
     * * 6 - Reserved, Microsoft Corporation
     * * 7 - Reserved for future definition
     */
    public function variant(): int
    {
        $octet = hexdec($this->clockSeqHiAndReserved);

        // test if the most significant bit is 0
        if (0b11111111 !== ($octet | 0b01111111)) {
            return static::VARIANT_RESERVED_NCS;
        }

        // test if the two most significant bits are 10
        if (0b10111111 === ($octet | 0b00111111)) {
            return static::VARIANT_RFC_4122;
        }

        // test if the three most significant bits are 110
        if (0b11011111 === ($octet | 0b00011111)) {
            return static::VARIANT_RESERVED_MICROSOFT;
        }

        // the most significant bits are 111
        return static::VARIANT_RESERVED_FUTURE;
    }

    /**
     * Retrieves a Uniform Resource Name (URN) representation
     */
    public function toUrn(): string
    {
        return sprintf('urn:uuid:%s', $this->toString());
    }

    /**
     * Retrieves a hexadecimal string representation
     */
    public function toHex(): string
    {
        return sprintf(
            '%s%s%s%s%s%s',
            $this->timeLow,
            $this->timeMid,
            $this->timeHiAndVersion,
            $this->clockSeqHiAndReserved,
            $this->clockSeqLow,
            $this->node
        );
    }

    /**
     * Retrieves a 16-byte string representation in network byte order
     */
    public function toBytes(): string
    {
        $bytes = '';
        $hex = $this->toHex();

        foreach (range(0, 30, 2) as $i) {
            $bytes .= chr(hexdec(substr($hex, $i, 2)));
        }

        return $bytes;
    }

    /**
     * Retrieves an array representation
     *
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.2
     */
    public function toArray(): array
    {
        return [
            'time_low'                  => $this->timeLow,
            'time_mid'                  => $this->timeMid,
            'time_hi_and_version'       => $this->timeHiAndVersion,
            'clock_seq_hi_and_reserved' => $this->clockSeqHiAndReserved,
            'clock_seq_low'             => $this->clockSeqLow,
            'node'                      => $this->node
        ];
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return sprintf(
            '%s-%s-%s-%s%s-%s',
            $this->timeLow,
            $this->timeMid,
            $this->timeHiAndVersion,
            $this->clockSeqHiAndReserved,
            $this->clockSeqLow,
            $this->node
        );
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

        $thisHexChars = $this->toHex();
        $thatHexChars = $object->toHex();

        for ($i = 0; $i < 32; $i++) {
            $thisHexChar = $thisHexChars[$i];
            $thatHexChar = $thatHexChars[$i];
            if ($thisHexChar > $thatHexChar) {
                return 1;
            }
            if ($thisHexChar < $thatHexChar) {
                return -1;
            }
        }

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function equals(mixed $object): bool
    {
        if ($this === $object) {
            return true;
        }

        if (!Validate::areSameType($this, $object)) {
            return false;
        }

        return $this->toHex() === $object->toHex();
    }

    /**
     * @inheritDoc
     */
    public function hashValue(): string
    {
        return $this->toHex();
    }

    /**
     * Creates a UUID version 1
     *
     * Since this object does not have access to system state or other
     * persistence, the node and clock sequence may be passed from another
     * layer to this method following the rules of section 4.2.1.
     *
     * If node, clock sequence, or timestamp values are not passed in, they
     * will be generated.
     *
     * Generating the node and clock sequence are not recommended for this UUID
     * version. The current timestamp can be retrieved in the correct format
     * from the `Uuid::timestamp()` static method.
     *
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.5
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.6
     * @link http://tools.ietf.org/html/rfc4122#section-4.2.1
     *
     * @param string|null $node      The node ID or MAC address
     * @param int|null    $clockSeq  The clock sequence as a 14-bit integer
     * @param string|null $timestamp The timestamp
     *
     * @throws DomainException When clockSeq is not 14-bit unsigned
     * @throws DomainException When the node is not 6 hex octets
     * @throws DomainException When timestamp is not 8 hex octets
     */
    protected static function uuid1(?string $node = null, ?int $clockSeq = null, ?string $timestamp = null): static
    {
        if ($timestamp === null) {
            $timestamp = static::timestamp();
        }
        $timestamp = strtolower($timestamp);
        static::guardTimestamp($timestamp);

        // format time fields with version
        $timeLow = substr($timestamp, 8, 8);
        $timeMid = substr($timestamp, 4, 4);
        $timeHi = substr($timestamp, 0, 4);
        $timeHiAndVersion = hexdec($timeHi);
        $timeHiAndVersion |= (static::VERSION_TIME << 12);
        $timeHiAndVersion = sprintf('%04x', $timeHiAndVersion);

        if ($clockSeq === null) {
            $clockSeq = random_int(0b0, 0b11111111111111);
        }
        static::guardClockSeq($clockSeq);

        // format clock sequence with variant
        $clockSeqLow = sprintf('%02x', $clockSeq & 0xff);
        $clockSeqHiAndReserved = ($clockSeq & 0x3f00) >> 8;
        $clockSeqHiAndReserved |= 0x80;
        $clockSeqHiAndReserved = sprintf('%02x', $clockSeqHiAndReserved);

        if ($node === null) {
            $node = bin2hex(random_bytes(6));
        } else {
            // remove formatting from MAC address if present
            $node = str_replace([':', '-', '.'], '', $node);
        }
        $node = strtolower($node);
        static::guardNode($node);

        return new static(
            $timeLow,
            $timeMid,
            $timeHiAndVersion,
            $clockSeqHiAndReserved,
            $clockSeqLow,
            $node
        );
    }

    /**
     * Creates a UUID version 3
     *
     * @throws DomainException When the namespace is not a valid UUID
     */
    protected static function uuid3(Uuid|string $namespace, string $name): static
    {
        if (!($namespace instanceof self)) {
            $namespace = static::parse($namespace);
        }

        $hash = md5($namespace->toBytes().$name);

        return static::fromUnformatted($hash, static::VERSION_MD5);
    }

    /**
     * Creates a UUID version 4
     */
    protected static function uuid4(): static
    {
        $hex = bin2hex(random_bytes(16));

        return static::fromUnformatted($hex, static::VERSION_RANDOM);
    }

    /**
     * Creates a UUID version 5
     *
     * @throws DomainException When the namespace is not a valid UUID
     */
    protected static function uuid5(Uuid|string $namespace, string $name): static
    {
        if (!($namespace instanceof self)) {
            $namespace = static::parse($namespace);
        }

        $hash = sha1($namespace->toBytes().$name);

        return static::fromUnformatted($hash, static::VERSION_SHA1);
    }

    /**
     * Creates a formatted UUID from a hexadecimal string
     *
     * This method multiplexes the version and variant bits as described in
     * sections 4.3 and 4.4 of RFC 4122. Supports versions 3, 4, and 5 UUIDs.
     *
     * @link http://tools.ietf.org/html/rfc4122#section-4.3
     * @link http://tools.ietf.org/html/rfc4122#section-4.4
     */
    protected static function fromUnformatted(string $hex, int $version): static
    {
        $timeLow = substr($hex, 0, 8);
        $timeMid = substr($hex, 8, 4);
        $timeHi = substr($hex, 12, 4);
        $clockSeqHi = substr($hex, 16, 2);
        $clockSeqLow = substr($hex, 18, 2);
        $node = substr($hex, 20, 12);

        // version
        $timeHiAndVersion = hexdec($timeHi);
        $timeHiAndVersion &= 0x0fff;
        $timeHiAndVersion |= ($version << 12);
        $timeHiAndVersion = sprintf('%04x', $timeHiAndVersion);

        // variant
        $clockSeqHiAndReserved = hexdec($clockSeqHi);
        $clockSeqHiAndReserved &= 0x3f;
        $clockSeqHiAndReserved |= 0x80;
        $clockSeqHiAndReserved = sprintf('%02x', $clockSeqHiAndReserved);

        return new static(
            $timeLow,
            $timeMid,
            $timeHiAndVersion,
            $clockSeqHiAndReserved,
            $clockSeqLow,
            $node
        );
    }

    /**
     * Validates a timestamp hexadecimal string
     *
     * @throws DomainException When timestamp is not 8 hex octets
     */
    protected static function guardTimestamp(string $timestamp): void
    {
        if (!preg_match('/\A[a-f0-9]{16}\z/', $timestamp)) {
            $message = 'Timestamp must be a 16 character hexadecimal string';
            throw new DomainException($message);
        }
    }

    /**
     * Validates a clock sequence integer
     *
     * @throws DomainException When clockSeq is not 14-bit unsigned
     */
    protected static function guardClockSeq(int $clockSeq): void
    {
        if ($clockSeq < 0b0 || $clockSeq > 0b11111111111111) {
            $message = 'Clock sequence must be a 14-bit unsigned integer value';
            throw new DomainException($message);
        }
    }

    /**
     * Validates a node hexadecimal string
     *
     * @throws DomainException When the node is not 6 hex octets
     */
    protected static function guardNode(string $node): void
    {
        if (!preg_match('/\A[a-f0-9]{12}\z/', $node)) {
            $message = 'Node must be a 12 character hexadecimal string';
            throw new DomainException($message);
        }
    }
}
