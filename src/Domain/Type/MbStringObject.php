<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\Common\Domain\Type\Mixin\StringOffsets;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\ImmutableException;
use Novuso\System\Exception\IndexException;
use Novuso\System\Utility\Assert;
use Traversable;

/**
 * Class MbStringObject
 */
final class MbStringObject extends ValueObject implements StringLiteral
{
    use StringOffsets;

    protected const ENCODING = 'UTF-8';

    protected int $length;

    /**
     * Constructs MbStringObject
     */
    public function __construct(protected string $value)
    {
        $this->length = (int) mb_strlen($this->value, static::ENCODING);
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value): static
    {
        return new static($value);
    }

    /**
     * Creates instance
     */
    public static function create(string $value): static
    {
        return new static($value);
    }

    /**
     * @inheritDoc
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function length(): int
    {
        return $this->length;
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return $this->length === 0;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->length;
    }

    /**
     * @inheritDoc
     */
    public function get(int $index): string
    {
        $length = $this->length;

        if ($index < -$length || $index > $length - 1) {
            $message = sprintf(
                'Index (%d) out of range[%d, %d]',
                $index,
                -$length,
                $length - 1
            );
            throw new IndexException($message);
        }

        if ($index < 0) {
            $index += $length;
        }

        return mb_substr($this->value, $index, 1, static::ENCODING);
    }

    /**
     * @inheritDoc
     */
    public function has(int $index): bool
    {
        $length = $this->length;

        if ($index < -$length || $index > $length - 1) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $index, mixed $character): void
    {
        throw new ImmutableException('Cannot modify immutable string');
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $index): string
    {
        Assert::isInt($index);

        return $this->get($index);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($index): bool
    {
        Assert::isInt($index);

        return $this->has($index);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($index): void
    {
        throw new ImmutableException('Cannot modify immutable string');
    }

    /**
     * @inheritDoc
     */
    public function chars(): ArrayList
    {
        $list = ArrayList::of('string');
        $value = $this->value;

        for ($i = 0; $i < $this->length; $i++) {
            $list->add(mb_substr($value, $i, 1, static::ENCODING));
        }

        return $list;
    }

    /**
     * @inheritDoc
     */
    public function contains(string $search, bool $caseSensitive = true): bool
    {
        return $this->indexOf($search, null, $caseSensitive) !== -1;
    }

    /**
     * @inheritDoc
     */
    public function startsWith(string $search, bool $caseSensitive = true): bool
    {
        if ($this->value === '') {
            return false;
        }

        if ($search === '') {
            return true;
        }

        $searchLength = mb_strlen($search, static::ENCODING);
        $start = mb_substr($this->value, 0, $searchLength, static::ENCODING);

        if ($caseSensitive === false) {
            $search = mb_strtolower($search, static::ENCODING);
            $start = mb_strtolower($start, static::ENCODING);
        }

        return $search === $start;
    }

    /**
     * @inheritDoc
     */
    public function endsWith(string $search, bool $caseSensitive = true): bool
    {
        $length = $this->length;

        if ($this->value === '') {
            return false;
        }

        if ($search === '') {
            return true;
        }

        $searchLength = mb_strlen($search, static::ENCODING);
        $end = mb_substr(
            $this->value,
            $length - $searchLength,
            $searchLength,
            static::ENCODING
        );

        if ($caseSensitive === false) {
            $search = mb_strtolower($search, static::ENCODING);
            $end = mb_strtolower($end, static::ENCODING);
        }

        return $search === $end;
    }

    /**
     * @inheritDoc
     */
    public function indexOf(string $search, ?int $start = null, bool $caseSensitive = true): int
    {
        if ($this->value === '') {
            return -1;
        }

        if ($start === null) {
            $start = 0;
        }
        $start = $this->prepareOffset($start, $this->length);

        if ($search === '') {
            return $start;
        }

        if ($caseSensitive === false) {
            $result = mb_stripos(
                $this->value,
                $search,
                $start,
                static::ENCODING
            );
        } else {
            $result = mb_strpos(
                $this->value,
                $search,
                $start,
                static::ENCODING
            );
        }

        if ($result === false) {
            return -1;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function lastIndexOf(string $search, ?int $stop = null, bool $caseSensitive = true): int
    {
        $length = $this->length;

        if ($this->value === '') {
            return -1;
        }

        if ($stop === null) {
            $stop = 0;
        }
        if ($stop !== 0) {
            $stop = $this->prepareOffset($stop, $length) - $length;
        }

        if ($search === '') {
            return $stop < 0 ? $stop + $length : $stop;
        }

        if ($caseSensitive === false) {
            $result = mb_strripos(
                $this->value,
                $search,
                $stop,
                static::ENCODING
            );
        } else {
            $result = mb_strrpos(
                $this->value,
                $search,
                $stop,
                static::ENCODING
            );
        }

        if ($result === false) {
            return -1;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function append(string $string): static
    {
        return static::create($this->value.$string);
    }

    /**
     * @inheritDoc
     */
    public function prepend(string $string): static
    {
        return static::create($string.$this->value);
    }

    /**
     * @inheritDoc
     */
    public function insert(int $index, string $string): static
    {
        $length = $this->length;

        $index = $this->prepareOffset($index, $length);
        $start = mb_substr($this->value, 0, $index, static::ENCODING);
        $end = mb_substr(
            $this->value,
            $index,
            $length - $index,
            static::ENCODING
        );

        return static::create($start.$string.$end);
    }

    /**
     * @inheritDoc
     */
    public function surround(string $string): static
    {
        return static::create($string.$this->value.$string);
    }

    /**
     * @inheritDoc
     */
    public function pad(int $length, ?string $char = null): static
    {
        $totalLength = $this->length;

        if ($length < 1) {
            $message = sprintf('Invalid length for padded string: %d', $length);
            throw new DomainException($message);
        }

        if ($char === null) {
            $char = ' ';
        }

        if (mb_strlen($char, static::ENCODING) !== 1) {
            $message = sprintf('Invalid string padding character: %s', $char);
            throw new DomainException($message);
        }

        if ($length < $totalLength) {
            return static::create($this->value);
        }

        $padLength = (float) ($length - $totalLength);

        return static::create(self::padString(
            $this->value,
            (int) floor($padLength / 2),
            (int) ceil($padLength / 2),
            $char
        ));
    }

    /**
     * @inheritDoc
     */
    public function padLeft(int $length, ?string $char = null): static
    {
        $totalLength = $this->length;

        if ($length < 1) {
            $message = sprintf('Invalid length for padded string: %d', $length);
            throw new DomainException($message);
        }

        if ($char === null) {
            $char = ' ';
        }

        if (mb_strlen($char, static::ENCODING) !== 1) {
            $message = sprintf('Invalid string padding character: %s', $char);
            throw new DomainException($message);
        }

        if ($length < $totalLength) {
            return static::create($this->value);
        }

        $padLength = $length - $totalLength;

        return static::create(self::padString(
            $this->value,
            $padLength,
            0,
            $char
        ));
    }

    /**
     * @inheritDoc
     */
    public function padRight(int $length, ?string $char = null): static
    {
        $totalLength = $this->length;

        if ($length < 1) {
            $message = sprintf('Invalid length for padded string: %d', $length);
            throw new DomainException($message);
        }

        if ($char === null) {
            $char = ' ';
        }

        if (mb_strlen($char, static::ENCODING) !== 1) {
            $message = sprintf('Invalid string padding character: %s', $char);
            throw new DomainException($message);
        }

        if ($length < $totalLength) {
            return static::create($this->value);
        }

        $padLength = $length - $totalLength;

        return static::create(self::padString(
            $this->value,
            0,
            $padLength,
            $char
        ));
    }

    /**
     * @inheritDoc
     */
    public function truncate(int $length, string $append = ''): static
    {
        if ($length < 1) {
            $message = sprintf(
                'Invalid length for truncated string: %d',
                $length
            );
            throw new DomainException($message);
        }

        $extra = mb_strlen($append, static::ENCODING);

        if ($extra > $length - 1) {
            $message = sprintf(
                'Append string length (%d) must be less than truncated length (%d)',
                $extra,
                $length
            );
            throw new DomainException($message);
        }

        $length -= $extra;

        if ($this->length <= $length) {
            return static::create($this->value.$append);
        }

        $truncated = mb_substr($this->value, 0, $length, static::ENCODING);

        return static::create($truncated.$append);
    }

    /**
     * @inheritDoc
     */
    public function truncateWords(int $length, string $append = ''): static
    {
        if ($length < 1) {
            $message = sprintf(
                'Invalid length for truncated string: %d',
                $length
            );
            throw new DomainException($message);
        }

        $extra = mb_strlen($append, static::ENCODING);

        if ($extra > $length - 1) {
            $message = sprintf(
                'Append string length (%d) must be less than truncated length (%d)',
                $extra,
                $length
            );
            throw new DomainException($message);
        }

        $length -= $extra;

        if ($this->length <= $length) {
            return static::create($this->value.$append);
        }

        $truncated = mb_substr($this->value, 0, $length, static::ENCODING);
        $last = mb_strpos($this->value, ' ', $length - 1, static::ENCODING);

        if ($last !== $length) {
            $last = mb_strrpos($truncated, ' ', 0, static::ENCODING);
            if ($last === false) {
                return static::create($truncated.$append);
            }
            $truncated = mb_substr($truncated, 0, $last, static::ENCODING);
        }

        return static::create($truncated.$append);
    }

    /**
     * @inheritDoc
     */
    public function repeat(int $multiplier): static
    {
        if ($multiplier < 1) {
            $message = sprintf('Invalid multiplier: %d', $multiplier);
            throw new DomainException($message);
        }

        return static::create(str_repeat($this->value, $multiplier));
    }

    /**
     * @inheritDoc
     */
    public function slice(int $start, ?int $stop = null): static
    {
        if ($stop === null) {
            $stop = 0;
        }

        $start = $this->prepareOffset($start, $this->length);
        $length = $this->prepareLengthFromStop($stop, $start, $this->length);

        $slice = mb_substr($this->value, $start, $length, static::ENCODING);

        return static::create($slice);
    }

    /**
     * @inheritDoc
     */
    public function substr(int $start, ?int $length = null): static
    {
        if ($length === null) {
            $length = 0;
        }

        $start = $this->prepareOffset($start, $this->length);
        $length = $this->prepareLength($length, $start, $this->length);

        $value = mb_substr($this->value, $start, $length, static::ENCODING);

        return static::create($value);
    }

    /**
     * @inheritDoc
     */
    public function split(string $delimiter = ' ', ?int $limit = null): ArrayList
    {
        if (empty($delimiter)) {
            throw new DomainException('Delimiter cannot be empty');
        }

        $pattern = sprintf('#%s#u', preg_quote($delimiter, '#'));

        if ($limit === null) {
            $parts = preg_split($pattern, $this->value);
        } else {
            $parts = preg_split($pattern, $this->value, $limit);
        }

        $list = ArrayList::of(static::class);

        foreach ($parts as $part) {
            $list->add(static::create($part));
        }

        return $list;
    }

    /**
     * @inheritDoc
     */
    public function chunk(int $size = 1): ArrayList
    {
        $value = $this->value();

        if ($size < 1) {
            $message = sprintf('Invalid chunk size: %d', $size);
            throw new DomainException($message);
        }

        $list = ArrayList::of(static::class);

        if ($this->length <= $size) {
            $list->add(static::create($value));
        } else {
            while ($value !== '') {
                $chunk = static::create(
                    mb_substr($value, 0, $size, static::ENCODING)
                );
                $list->add($chunk);
                $value = mb_substr(
                    $value,
                    $size,
                    mb_strlen($value, static::ENCODING) - $size,
                    static::ENCODING
                );
            }
        }

        return $list;
    }

    /**
     * @inheritDoc
     */
    public function replace($search, $replace): static
    {
        return static::create(str_replace($search, $replace, $this->value));
    }

    /**
     * @inheritDoc
     */
    public function trim(?string $mask = null): static
    {
        if ($mask === null) {
            return static::create(trim($this->value));
        }

        return static::create(trim($this->value, $mask));
    }

    /**
     * @inheritDoc
     */
    public function trimLeft(?string $mask = null): static
    {
        if ($mask === null) {
            return static::create(ltrim($this->value));
        }

        return static::create(ltrim($this->value, $mask));
    }

    /**
     * @inheritDoc
     */
    public function trimRight(?string $mask = null): static
    {
        if ($mask === null) {
            return static::create(rtrim($this->value));
        }

        return static::create(rtrim($this->value, $mask));
    }

    /**
     * @inheritDoc
     */
    public function expandTabs(int $tabSize = 4): static
    {
        if ($tabSize < 0) {
            $message = sprintf('Invalid tab size: %d', $tabSize);
            throw new DomainException($message);
        }

        $spaces = str_repeat(' ', $tabSize);

        return static::create(str_replace("\t", $spaces, $this->value));
    }

    /**
     * @inheritDoc
     */
    public function toLowerCase(): static
    {
        return static::create(mb_strtolower($this->value, static::ENCODING));
    }

    /**
     * @inheritDoc
     */
    public function toUpperCase(): static
    {
        return static::create(mb_strtoupper($this->value, static::ENCODING));
    }

    /**
     * @inheritDoc
     */
    public function toFirstLowerCase(): static
    {
        $length = $this->length;

        if ($length === 0) {
            return static::create('');
        }

        $first = mb_substr($this->value, 0, 1, static::ENCODING);

        if ($length < 2) {
            return static::create(mb_strtolower($first, static::ENCODING));
        }

        $remaining = mb_substr($this->value, 1, $length - 1, static::ENCODING);

        $value = mb_strtolower($first, static::ENCODING).$remaining;

        return static::create($value);
    }

    /**
     * @inheritDoc
     */
    public function toFirstUpperCase(): static
    {
        $length = $this->length;

        if ($length === 0) {
            return static::create('');
        }

        $first = mb_substr($this->value, 0, 1, static::ENCODING);

        if ($length < 2) {
            return static::create(mb_strtoupper($first, static::ENCODING));
        }

        $remaining = mb_substr($this->value, 1, $length - 1, static::ENCODING);

        $value = mb_strtoupper($first, static::ENCODING).$remaining;

        return static::create($value);
    }

    /**
     * @inheritDoc
     */
    public function toCamelCase(): static
    {
        return $this->toPascalCase()->toFirstLowerCase();
    }

    /**
     * @inheritDoc
     */
    public function toPascalCase(): static
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(self::capsCase($value));
    }

    /**
     * @inheritDoc
     */
    public function toSnakeCase(): static
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        $value = mb_strtolower(
            self::delimitString($value, '_'),
            static::ENCODING
        );

        return static::create($value);
    }

    /**
     * @inheritDoc
     */
    public function toLowerHyphenated(): static
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        $value = mb_strtolower(
            self::delimitString($value, '-'),
            static::ENCODING
        );

        return static::create($value);
    }

    /**
     * @inheritDoc
     */
    public function toUpperHyphenated(): static
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        $value = mb_strtoupper(
            self::delimitString($value, '-'),
            static::ENCODING
        );

        return static::create($value);
    }

    /**
     * @inheritDoc
     */
    public function toLowerUnderscored(): static
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        $value = mb_strtolower(
            self::delimitString($value, '_'),
            static::ENCODING
        );

        return static::create($value);
    }

    /**
     * @inheritDoc
     */
    public function toUpperUnderscored(): static
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        $value = mb_strtoupper(
            self::delimitString($value, '_'),
            static::ENCODING
        );

        return static::create($value);
    }

    /**
     * @inheritDoc
     */
    public function toSlug(): static
    {
        $slug = trim($this->value);
        $slug = iconv(static::ENCODING, 'ASCII//TRANSLIT', $slug);
        $slug = strtolower($slug);
        $slug = preg_replace('/\W/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        return static::create($slug);
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return $this->value;
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

        $strComp = strnatcmp($this->value, $object->value);

        return $strComp <=> 0;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return $this->chars();
    }

    /**
     * Applies padding to a string
     *
     * @param string $string The original string
     * @param int    $left   The left padding size
     * @param int    $right  The right padding size
     * @param string $char   The padding character
     *
     * @return string
     */
    protected static function padString(string $string, int $left, int $right, string $char): string
    {
        $leftPadding = str_repeat($char, $left);
        $rightPadding = str_repeat($char, $right);

        return $leftPadding.$string.$rightPadding;
    }

    /**
     * Applies caps formatting to a string
     *
     * @param string $string The original string
     *
     * @return string
     */
    protected static function capsCase(string $string): string
    {
        $output = [];

        if (preg_match('/\A[a-z0-9]+\z/ui', $string)
            && mb_strtoupper($string, static::ENCODING) !== $string) {
            $parts = self::explodeOnCaps($string);
        } else {
            $parts = self::explodeOnDelimiters($string);
        }

        foreach ($parts as $part) {
            $len = mb_strlen($part, static::ENCODING);
            $first = mb_substr($part, 0, 1, static::ENCODING);
            if ($len > 1) {
                $remaining = mb_substr($part, 1, $len - 1, static::ENCODING);
                $output[] = mb_strtoupper($first, static::ENCODING)
                    .mb_strtolower($remaining, static::ENCODING);
            } else {
                $output[] = mb_strtoupper($first, static::ENCODING);
            }
        }

        return implode('', $output);
    }

    /**
     * Applies delimiter formatting to a string
     *
     * @param string $string    The original string
     * @param string $delimiter The delimiter
     *
     * @return string
     */
    protected static function delimitString(string $string, string $delimiter): string
    {
        $output = [];

        if (preg_match('/\A[a-z0-9]+\z/ui', $string)
            && mb_strtoupper($string, static::ENCODING) !== $string) {
            $parts = self::explodeOnCaps($string);
        } else {
            $parts = self::explodeOnDelimiters($string);
        }

        foreach ($parts as $part) {
            $output[] = $part.$delimiter;
        }

        return rtrim(implode('', $output), $delimiter);
    }

    /**
     * Splits a string into a list on capital letters
     *
     * @param string $string The input string
     *
     * @return array
     */
    protected static function explodeOnCaps(string $string): array
    {
        $string = preg_replace('/\B([A-Z])/u', '_\1', $string);
        $string = preg_replace('/([0-9]+)/u', '_\1', $string);
        $string = preg_replace('/_+/', '_', $string);
        $string = trim($string, '_');

        return explode('_', $string);
    }

    /**
     * Splits a string into a list on non-word breaks
     *
     * @param string $string The input string
     *
     * @return array
     */
    protected static function explodeOnDelimiters(string $string): array
    {
        $string = preg_replace('/[^a-z0-9]+/ui', '_', $string);
        $string = trim($string, '_');

        return explode('_', $string);
    }
}
