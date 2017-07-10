<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\Common\Domain\Type\Traits\StringOffsets;
use Novuso\System\Collection\Api\IndexedList;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\ImmutableException;
use Novuso\System\Exception\IndexException;
use Traversable;
use function Novuso\Common\Functions\{
    list_of,
    var_print,
    same_type
};

/**
 * CString is a wrapper for a standard ASCII string
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CString extends ValueObject implements StringLiteral
{
    use StringOffsets;

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
     * Constructs CString
     *
     * @param string $value The string value
     */
    public function __construct(string $value)
    {
        $this->length = strlen($value);
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): CString
    {
        return new static($value);
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
        $value = $this->value;
        $length = $this->length;

        if ($index < -$length || $index > $length - 1) {
            $message = sprintf('Index (%d) out of range[%d, %d]', $index, -$length, $length - 1);
            throw new IndexException($message);
        }

        if ($index < 0) {
            $index += $length;
        }

        return $value[$index];
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
            is_int($index),
            sprintf('Invalid character index: %s', var_print($index))
        );

        return $this->get($index);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($index): bool
    {
        assert(
            is_int($index),
            sprintf('Invalid character index: %s', var_print($index))
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
        $list = list_of('string');

        foreach (str_split($this->value) as $char) {
            $list->add($char);
        }

        return $list;
    }

    /**
     * {@inheritdoc}
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

        $searchlen = strlen($search);
        $start = substr($this->value, 0, $searchlen);

        if ($caseSensitive === false) {
            $search = strtolower($search);
            $start = strtolower($start);
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

        $searchlen = strlen($search);
        $end = substr($this->value, $length - $searchlen, $searchlen);

        if ($caseSensitive === false) {
            $search = strtolower($search);
            $end = strtolower($end);
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
        $start = substr($this->value, 0, $index);
        $end = substr($this->value, $index, $length - $index);

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

        if (strlen($char) !== 1) {
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

        if (strlen($char) !== 1) {
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

        if (strlen($char) !== 1) {
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

        $extra = strlen($append);

        if ($extra > $strlen - 1) {
            $message = sprintf('Append string length (%d) must be less than truncated length (%d)', $extra, $strlen);
            throw new DomainException($message);
        }

        $strlen -= $extra;

        if ($this->length <= $strlen) {
            return static::create($this->value.$append);
        }

        return static::create(substr($this->value, 0, $strlen).$append);
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

        $extra = strlen($append);

        if ($extra > $strlen - 1) {
            $message = sprintf('Append string length (%d) must be less than truncated length (%d)', $extra, $strlen);
            throw new DomainException($message);
        }

        $strlen -= $extra;

        if ($this->length <= $strlen) {
            return static::create($this->value.$append);
        }

        $truncated = substr($this->value, 0, $strlen);
        $last = strpos($this->value, ' ', $strlen - 1);

        if ($last !== $strlen) {
            $last = strrpos($truncated, ' ', 0);
            if ($last === false) {
                return static::create($truncated.$append);
            }
            $truncated = substr($truncated, 0, $last);
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

        return static::create(substr($this->value, $start, $strlen));
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

        return static::create(substr($this->value, $start, $strlen));
    }

    /**
     * {@inheritdoc}
     */
    public function split(string $delimiter = ' ', ?int $limit = null): IndexedList
    {
        if (empty($delimiter)) {
            throw new DomainException('Delimiter cannot be empty');
        }

        if ($limit === null) {
            $parts = explode($delimiter, $this->value);
        } else {
            $parts = explode($delimiter, $this->value, $limit);
        }

        $list = list_of(static::class);

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
        if ($size < 1) {
            $message = sprintf('Invalid chunk size: %d', $size);
            throw new DomainException($message);
        }

        $parts = str_split($this->value, $size);
        $list = list_of(static::class);

        foreach ($parts as $part) {
            $list->add(static::create($part));
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
        return static::create(strtolower($this->value));
    }

    /**
     * {@inheritdoc}
     */
    public function toUpperCase(): StringLiteral
    {
        return static::create(strtoupper($this->value));
    }

    /**
     * {@inheritdoc}
     */
    public function toFirstLowerCase(): StringLiteral
    {
        return static::create(lcfirst($this->value));
    }

    /**
     * {@inheritdoc}
     */
    public function toFirstUpperCase(): StringLiteral
    {
        return static::create(ucfirst($this->value));
    }

    /**
     * {@inheritdoc}
     */
    public function toCamelCase(): StringLiteral
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(lcfirst(self::capsCase($value)));
    }

    /**
     * {@inheritdoc}
     */
    public function toPascalCase(): StringLiteral
    {
        $value = trim($this->value);
        $length = strlen($value);

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
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtolower(self::delimitString($value, '_')));
    }

    /**
     * {@inheritdoc}
     */
    public function toLowerHyphenated(): StringLiteral
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtolower(self::delimitString($value, '-')));
    }

    /**
     * {@inheritdoc}
     */
    public function toUpperHyphenated(): StringLiteral
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtoupper(self::delimitString($value, '-')));
    }

    /**
     * {@inheritdoc}
     */
    public function toLowerUnderscored(): StringLiteral
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtolower(self::delimitString($value, '_')));
    }

    /**
     * {@inheritdoc}
     */
    public function toUpperUnderscored(): StringLiteral
    {
        $value = trim($this->value);
        $length = strlen($value);

        if ($length === 0) {
            return static::create('');
        }

        return static::create(strtoupper(self::delimitString($value, '_')));
    }

    /**
     * {@inheritdoc}
     */
    public function toSlug(): StringLiteral
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
            same_type($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        $strComp = strnatcmp($this->value, $object->value);

        return $strComp <=> 0;
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

        if (preg_match('/\A[a-z0-9]+\z/i', $string) && strtoupper($string) !== $string) {
            $parts = self::explodeOnCaps($string);
        } else {
            $parts = self::explodeOnDelims($string);
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
    private static function delimitString(string $string, string $delimiter): string
    {
        $output = [];

        if (preg_match('/\A[a-z0-9]+\z/ui', $string) && strtoupper($string) !== $string) {
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
    private static function explodeOnDelims(string $string): array
    {
        $string = preg_replace('/[^a-z0-9]+/i', '_', $string);
        $string = trim($string, '_');

        return explode('_', $string);
    }
}
