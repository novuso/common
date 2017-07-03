<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\Common\Domain\Type\Traits\StringOffsets;
use Novuso\System\Collection\Api\IndexedList;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\ImmutableException;
use Novuso\System\Exception\IndexException;
use Novuso\System\Utility\Validate;
use Novuso\System\Utility\VarPrinter;
use Traversable;

/**
 * MbString is a wrapper for a multibyte string
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class MbString extends ValueObject implements StringLiteral
{
    use StringOffsets;

    /**
     * String encoding
     *
     * @var string
     */
    protected const ENCODING = 'UTF-8';

    /**
     * String value
     *
     * @var string
     */
    protected $value;

    /**
     * String length
     *
     * @var int
     */
    protected $length;

    /**
     * Constructs MbString
     *
     * @param string $value The string value
     */
    public function __construct(string $value)
    {
        $this->length = (int) mb_strlen($value, static::ENCODING);
        $this->value = $value;
    }

    /**
     * Creates instance
     *
     * @param string $value The string value
     *
     * @return StringLiteral
     */
    public static function create(string $value): StringLiteral
    {
        return new static($value);
    }

    /**
     * {@inheritdoc}
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function length(): int
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return $this->length === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $index): string
    {
        $length = $this->length;

        if ($index < -$length || $index > $length - 1) {
            $message = sprintf('Index (%d) out of range[%d, %d]', $index, -$length, $length - 1);
            throw new IndexException($message);
        }

        if ($index < 0) {
            $index += $length;
        }

        return mb_substr($this->value, $index, 1, static::ENCODING);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function offsetSet($index, $char): void
    {
        throw new ImmutableException('Cannot modify immutable string');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($index): string
    {
        assert(
            Validate::isInt($index),
            sprintf('Invalid character index: %s', VarPrinter::toString($index))
        );

        return $this->get($index);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($index): bool
    {
        assert(
            Validate::isInt($index),
            sprintf('Invalid character index: %s', VarPrinter::toString($index))
        );

        return $this->has($index);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($index): void
    {
        throw new ImmutableException('Cannot modify immutable string');
    }

    /**
     * {@inheritdoc}
     */
    public function chars(): IndexedList
    {
        $list = ArrayList::of('string');
        $value = $this->value;

        for ($i = 0; $i < $this->length; $i++) {
            $list->add(mb_substr($value, $i, 1, static::ENCODING));
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function contains(string $search, bool $caseSensitive = true): bool
    {
        return $this->indexOf($search, null, $caseSensitive) !== -1;
    }

    /**
     * {@inheritdoc}
     */
    public function startsWith(string $search, bool $caseSensitive = true): bool
    {
        if ($this->value === '') {
            return false;
        }

        if ($search === '') {
            return true;
        }

        $searchlen = mb_strlen($search, static::ENCODING);
        $start = mb_substr($this->value, 0, $searchlen, static::ENCODING);

        if ($caseSensitive === false) {
            $search = mb_strtolower($search, static::ENCODING);
            $start = mb_strtolower($start, static::ENCODING);
        }

        return $search === $start;
    }

    /**
     * {@inheritdoc}
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

        $searchlen = mb_strlen($search, static::ENCODING);
        $end = mb_substr($this->value, $length - $searchlen, $searchlen, static::ENCODING);

        if ($caseSensitive === false) {
            $search = mb_strtolower($search, static::ENCODING);
            $end = mb_strtolower($end, static::ENCODING);
        }

        return $search === $end;
    }

    /**
     * {@inheritdoc}
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
            $result = mb_stripos($this->value, $search, $start, static::ENCODING);
        } else {
            $result = mb_strpos($this->value, $search, $start, static::ENCODING);
        }

        if ($result === false) {
            return -1;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
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
            $result = mb_strripos($this->value, $search, $stop, static::ENCODING);
        } else {
            $result = mb_strrpos($this->value, $search, $stop, static::ENCODING);
        }

        if ($result === false) {
            return -1;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function append(string $string): StringLiteral
    {
        return static::create($this->value.$string);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(string $string): StringLiteral
    {
        return static::create($string.$this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function insert(int $index, string $string): StringLiteral
    {
        $length = $this->length;

        $index = $this->prepareOffset($index, $length);
        $start = mb_substr($this->value, 0, $index, static::ENCODING);
        $end = mb_substr($this->value, $index, $length - $index, static::ENCODING);

        return static::create($start.$string.$end);
    }

    /**
     * {@inheritdoc}
     */
    public function surround(string $string): StringLiteral
    {
        return static::create($string.$this->value.$string);
    }

    /**
     * {@inheritdoc}
     */
    public function pad(int $strlen, ?string $char = null): StringLiteral
    {
        $length = $this->length;

        if ($strlen < 1) {
            $message = sprintf('Invalid length for padded string: %d', $strlen);
            throw new DomainException($message);
        }

        if ($char === null) {
            $char = ' ';
        }

        if (mb_strlen($char, static::ENCODING) !== 1) {
            $message = sprintf('Invalid string padding character: %s', $char);
            throw new DomainException($message);
        }

        if ($strlen < $length) {
            return static::create($this->value);
        }

        $padlen = (float) ($strlen - $length);

        return static::create(self::padString($this->value, (int) floor($padlen / 2), (int) ceil($padlen / 2), $char));
    }

    /**
     * {@inheritdoc}
     */
    public function padLeft(int $strlen, ?string $char = null): StringLiteral
    {
        $length = $this->length;

        if ($strlen < 1) {
            $message = sprintf('Invalid length for padded string: %d', $strlen);
            throw new DomainException($message);
        }

        if ($char === null) {
            $char = ' ';
        }

        if (mb_strlen($char, static::ENCODING) !== 1) {
            $message = sprintf('Invalid string padding character: %s', $char);
            throw new DomainException($message);
        }

        if ($strlen < $length) {
            return static::create($this->value);
        }

        $padlen = $strlen - $length;

        return static::create(self::padString($this->value, $padlen, 0, $char));
    }

    /**
     * {@inheritdoc}
     */
    public function padRight(int $strlen, ?string $char = null): StringLiteral
    {
        $length = $this->length;

        if ($strlen < 1) {
            $message = sprintf('Invalid length for padded string: %d', $strlen);
            throw new DomainException($message);
        }

        if ($char === null) {
            $char = ' ';
        }

        if (mb_strlen($char, static::ENCODING) !== 1) {
            $message = sprintf('Invalid string padding character: %s', $char);
            throw new DomainException($message);
        }

        if ($strlen < $length) {
            return static::create($this->value);
        }

        $padlen = $strlen - $length;

        return static::create(self::padString($this->value, 0, $padlen, $char));
    }

    /**
     * {@inheritdoc}
     */
    public function truncate(int $strlen, string $append = ''): StringLiteral
    {
        if ($strlen < 1) {
            $message = sprintf('Invalid length for truncated string: %d', $strlen);
            throw new DomainException($message);
        }

        $extra = mb_strlen($append, static::ENCODING);

        if ($extra > $strlen - 1) {
            $message = sprintf('Append string length (%d) must be less than truncated length (%d)', $extra, $strlen);
            throw new DomainException($message);
        }

        $strlen -= $extra;

        if ($this->length <= $strlen) {
            return static::create($this->value.$append);
        }

        return static::create(mb_substr($this->value, 0, $strlen, static::ENCODING).$append);
    }

    /**
     * {@inheritdoc}
     */
    public function truncateWords(int $strlen, string $append = ''): StringLiteral
    {
        if ($strlen < 1) {
            $message = sprintf('Invalid length for truncated string: %d', $strlen);
            throw new DomainException($message);
        }

        $extra = mb_strlen($append, static::ENCODING);

        if ($extra > $strlen - 1) {
            $message = sprintf('Append string length (%d) must be less than truncated length (%d)', $extra, $strlen);
            throw new DomainException($message);
        }

        $strlen -= $extra;

        if ($this->length <= $strlen) {
            return static::create($this->value.$append);
        }

        $truncated = mb_substr($this->value, 0, $strlen, static::ENCODING);
        $last = mb_strpos($this->value, ' ', $strlen - 1, static::ENCODING);

        if ($last !== $strlen) {
            $last = mb_strrpos($truncated, ' ', 0, static::ENCODING);
            if ($last === false) {
                return static::create($truncated.$append);
            }
            $truncated = mb_substr($truncated, 0, $last, static::ENCODING);
        }

        return static::create($truncated.$append);
    }

    /**
     * {@inheritdoc}
     */
    public function repeat(int $multiplier): StringLiteral
    {
        if ($multiplier < 1) {
            $message = sprintf('Invalid multiplier: %d', $multiplier);
            throw new DomainException($message);
        }

        return static::create(str_repeat($this->value, $multiplier));
    }

    /**
     * {@inheritdoc}
     */
    public function slice(int $start, ?int $stop = null): StringLiteral
    {
        if ($stop === null) {
            $stop = 0;
        }

        $start = $this->prepareOffset($start, $this->length);
        $strlen = $this->prepareLengthFromStop($stop, $start, $this->length);

        return static::create(mb_substr($this->value, $start, $strlen, static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function substr(int $start, ?int $strlen = null): StringLiteral
    {
        if ($strlen === null) {
            $strlen = 0;
        }

        $start = $this->prepareOffset($start, $this->length);
        $strlen = $this->prepareLength($strlen, $start, $this->length);

        return static::create(mb_substr($this->value, $start, $strlen, static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function split(string $delimiter = ' ', ?int $limit = null): IndexedList
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
     * {@inheritdoc}
     */
    public function chunk(int $size = 1): IndexedList
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
                $list->add(static::create(mb_substr($value, 0, $size, static::ENCODING)));
                $value = mb_substr($value, $size, mb_strlen($value, static::ENCODING) - $size, static::ENCODING);
            }
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function replace($search, $replace): StringLiteral
    {
        return static::create(str_replace($search, $replace, $this->value));
    }

    /**
     * {@inheritdoc}
     */
    public function trim(?string $mask = null): StringLiteral
    {
        if ($mask === null) {
            return static::create(trim($this->value));
        }

        return static::create(trim($this->value, $mask));
    }

    /**
     * {@inheritdoc}
     */
    public function trimLeft(?string $mask = null): StringLiteral
    {
        if ($mask === null) {
            return static::create(ltrim($this->value));
        }

        return static::create(ltrim($this->value, $mask));
    }

    /**
     * {@inheritdoc}
     */
    public function trimRight(?string $mask = null): StringLiteral
    {
        if ($mask === null) {
            return static::create(rtrim($this->value));
        }

        return static::create(rtrim($this->value, $mask));
    }

    /**
     * {@inheritdoc}
     */
    public function expandTabs(int $tabsize = 4): StringLiteral
    {
        if ($tabsize < 0) {
            $message = sprintf('Invalid tabsize: %d', $tabsize);
            throw new DomainException($message);
        }

        $spaces = str_repeat(' ', $tabsize);

        return static::create(str_replace("\t", $spaces, $this->value));
    }

    /**
     * {@inheritdoc}
     */
    public function toLowerCase(): StringLiteral
    {
        return static::create(mb_strtolower($this->value, static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function toUpperCase(): StringLiteral
    {
        return static::create(mb_strtoupper($this->value, static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function toFirstLowerCase(): StringLiteral
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

        return static::create(mb_strtolower($first, static::ENCODING).$remaining);
    }

    /**
     * {@inheritdoc}
     */
    public function toFirstUpperCase(): StringLiteral
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

        return static::create(mb_strtoupper($first, static::ENCODING).$remaining);
    }

    /**
     * {@inheritdoc}
     */
    public function toCamelCase(): StringLiteral
    {
        return $this->toPascalCase()->toFirstLowerCase();
    }

    /**
     * {@inheritdoc}
     */
    public function toPascalCase(): StringLiteral
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(self::capsCase($value));
    }

    /**
     * {@inheritdoc}
     */
    public function toSnakeCase(): StringLiteral
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(mb_strtolower(self::delimitString($value, '_'), static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function toLowerHyphenated(): StringLiteral
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(mb_strtolower(self::delimitString($value, '-'), static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function toUpperHyphenated(): StringLiteral
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(mb_strtoupper(self::delimitString($value, '-'), static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function toLowerUnderscored(): StringLiteral
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(mb_strtolower(self::delimitString($value, '_'), static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function toUpperUnderscored(): StringLiteral
    {
        $value = trim($this->value);
        $length = mb_strlen($value, static::ENCODING);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(mb_strtoupper(self::delimitString($value, '_'), static::ENCODING));
    }

    /**
     * {@inheritdoc}
     */
    public function toSlug(): StringLiteral
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
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        assert(
            Validate::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        $strComp = strnatcmp($this->value, $object->value);

        /** @var int $comp */
        $comp = $strComp <=> 0;

        return $comp;
    }

    /**
     * {@inheritdoc}
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
    private static function padString(string $string, int $left, int $right, string $char): string
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
    private static function capsCase(string $string): string
    {
        $output = [];

        if (preg_match('/\A[a-z0-9]+\z/ui', $string) && mb_strtoupper($string, static::ENCODING) !== $string) {
            $parts = self::explodeOnCaps($string);
        } else {
            $parts = self::explodeOnDelims($string);
        }

        foreach ($parts as $part) {
            $len = mb_strlen($part, static::ENCODING);
            $first = mb_substr($part, 0, 1, static::ENCODING);
            if ($len > 1) {
                $remaining = mb_substr($part, 1, $len - 1, static::ENCODING);
                $output[] = mb_strtoupper($first, static::ENCODING).mb_strtolower($remaining, static::ENCODING);
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
    private static function delimitString(string $string, string $delimiter): string
    {
        $output = [];

        if (preg_match('/\A[a-z0-9]+\z/ui', $string) && mb_strtoupper($string, static::ENCODING) !== $string) {
            $parts = self::explodeOnCaps($string);
        } else {
            $parts = self::explodeOnDelims($string);
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
    private static function explodeOnCaps(string $string): array
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
    private static function explodeOnDelims(string $string): array
    {
        $string = preg_replace('/[^a-z0-9]+/ui', '_', $string);
        $string = trim($string, '_');

        return explode('_', $string);
    }
}
