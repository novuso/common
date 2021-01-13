<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\Common\Domain\Type\Mixin\StringOffsets;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\ImmutableException;
use Novuso\System\Exception\IndexException;
use Novuso\System\Utility\Assert;
use Traversable;

/**
 * Class StringObject
 */
final class StringObject extends ValueObject implements StringLiteral
{
    use StringOffsets;

    protected int $length;

    /**
     * Constructs StringObject
     */
    public function __construct(protected string $value)
    {
        $this->length = strlen($this->value);
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
        $value = $this->value;
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

        return $value[$index];
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
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new ImmutableException('Cannot modify immutable string');
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): string
    {
        Assert::isInt($offset);

        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        Assert::isInt($offset);

        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new ImmutableException('Cannot modify immutable string');
    }

    /**
     * @inheritDoc
     */
    public function chars(): ArrayList
    {
        $list = ArrayList::of('string');

        foreach (str_split($this->value) as $char) {
            $list->add($char);
        }

        return $list;
    }

    /**
     * @inheritDoc
     */
    public function contains(string $search, bool $caseSensitive = true): bool
    {
        if ($this->value === '') {
            return false;
        }

        if ($search === '') {
            return true;
        }

        if ($caseSensitive === false) {
            $result = stripos($this->value, $search);
        } else {
            $result = strpos($this->value, $search);
        }

        if ($result === false) {
            return false;
        }

        return true;
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

        $searchLength = strlen($search);
        $start = substr($this->value, 0, $searchLength);

        if ($caseSensitive === false) {
            $search = strtolower($search);
            $start = strtolower($start);
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

        $searchLength = strlen($search);
        $end = substr($this->value, $length - $searchLength, $searchLength);

        if ($caseSensitive === false) {
            $search = strtolower($search);
            $end = strtolower($end);
        }

        return $search === $end;
    }

    /**
     * @inheritDoc
     */
    public function indexOf(
        string $search,
        ?int $start = null,
        bool $caseSensitive = true
    ): int {
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
            $result = stripos($this->value, $search, $start);
        } else {
            $result = strpos($this->value, $search, $start);
        }

        if ($result === false) {
            return -1;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function lastIndexOf(
        string $search,
        ?int $stop = null,
        bool $caseSensitive = true
    ): int {
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
            $result = strripos($this->value, $search, $stop);
        } else {
            $result = strrpos($this->value, $search, $stop);
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
        $start = substr($this->value, 0, $index);
        $end = substr($this->value, $index, $length - $index);

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

        if (strlen($char) !== 1) {
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

        if (strlen($char) !== 1) {
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

        if (strlen($char) !== 1) {
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

        $extra = strlen($append);

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

        return static::create(substr($this->value, 0, $length).$append);
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

        $extra = strlen($append);

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

        $truncated = substr($this->value, 0, $length);
        $last = strpos($this->value, ' ', $length - 1);

        if ($last !== $length) {
            $last = strrpos($truncated, ' ', 0);
            if ($last === false) {
                return static::create($truncated.$append);
            }
            $truncated = substr($truncated, 0, $last);
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

        return static::create(substr($this->value, $start, $length));
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

        return static::create(substr($this->value, $start, $length));
    }

    /**
     * @inheritDoc
     */
    public function split(
        string $delimiter = ' ',
        ?int $limit = null
    ): ArrayList {
        if (empty($delimiter)) {
            throw new DomainException('Delimiter cannot be empty');
        }

        if ($limit === null) {
            $parts = explode($delimiter, $this->value);
        } else {
            $parts = explode($delimiter, $this->value, $limit);
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
        if ($size < 1) {
            $message = sprintf('Invalid chunk size: %d', $size);
            throw new DomainException($message);
        }

        $parts = str_split($this->value, $size);
        $list = ArrayList::of(static::class);

        foreach ($parts as $part) {
            $list->add(static::create($part));
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
        return static::create(strtolower($this->value));
    }

    /**
     * @inheritDoc
     */
    public function toUpperCase(): static
    {
        return static::create(strtoupper($this->value));
    }

    /**
     * @inheritDoc
     */
    public function toFirstLowerCase(): static
    {
        return static::create(lcfirst($this->value));
    }

    /**
     * @inheritDoc
     */
    public function toFirstUpperCase(): static
    {
        return static::create(ucfirst($this->value));
    }

    /**
     * @inheritDoc
     */
    public function toCamelCase(): static
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(lcfirst(self::capsCase($value)));
    }

    /**
     * @inheritDoc
     */
    public function toPascalCase(): static
    {
        $value = trim($this->value);
        $length = strlen($value);

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
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtolower(self::delimitString($value, '_')));
    }

    /**
     * @inheritDoc
     */
    public function toLowerHyphenated(): static
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtolower(self::delimitString($value, '-')));
    }

    /**
     * @inheritDoc
     */
    public function toUpperHyphenated(): static
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtoupper(self::delimitString($value, '-')));
    }

    /**
     * @inheritDoc
     */
    public function toLowerUnderscored(): static
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtolower(self::delimitString($value, '_')));
    }

    /**
     * @inheritDoc
     */
    public function toUpperUnderscored(): static
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtoupper(self::delimitString($value, '_')));
    }

    /**
     * @inheritDoc
     */
    public function toSlug(): static
    {
        $slug = trim($this->value);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
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
    protected static function padString(
        string $string,
        int $left,
        int $right,
        string $char
    ): string {
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

        if (
            preg_match('/\A[a-z0-9]+\z/i', $string)
            && strtoupper($string) !== $string
        ) {
            $parts = self::explodeOnCaps($string);
        } else {
            $parts = self::explodeOnDelimiters($string);
        }

        foreach ($parts as $part) {
            $output[] = ucfirst(strtolower($part));
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
    protected static function delimitString(
        string $string,
        string $delimiter
    ): string {
        $output = [];

        if (
            preg_match('/\A[a-z0-9]+\z/ui', $string)
            && strtoupper($string) !== $string
        ) {
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
        $string = preg_replace('/\B([A-Z])/', '_\1', $string);
        $string = preg_replace('/([0-9]+)/', '_\1', $string);
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
        $string = preg_replace('/[^a-z0-9]+/i', '_', $string);
        $string = trim($string, '_');

        return explode('_', $string);
    }
}
