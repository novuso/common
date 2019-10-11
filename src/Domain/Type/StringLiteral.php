<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Novuso\System\Collection\Type\Sequence;
use Novuso\System\Exception\AssertionException;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\ImmutableException;
use Novuso\System\Exception\IndexException;
use Novuso\System\Type\Comparable;
use Traversable;

/**
 * Interface StringLiteral
 */
interface StringLiteral extends ArrayAccess, Comparable, Countable, IteratorAggregate, Value
{
    /**
     * Retrieves the string value
     *
     * @return string
     */
    public function value(): string;

    /**
     * Retrieves the string length
     *
     * @return int
     */
    public function length(): int;

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Retrieves the character count
     *
     * @return int
     */
    public function count(): int;

    /**
     * Retrieves the character at an index
     *
     * @param int $index The index
     *
     * @return string
     *
     * @throws IndexException When the index is invalid
     */
    public function get(int $index): string;

    /**
     * Checks if an index is valid
     *
     * @param int $index The index
     *
     * @return bool
     */
    public function has(int $index): bool;

    /**
     * Not implemented
     *
     * @param int    $index     The index
     * @param string $character The character
     *
     * @return void
     *
     * @throws ImmutableException When called
     */
    public function offsetSet($index, $character): void;

    /**
     * Retrieves the character at an index
     *
     * @param int $index The index
     *
     * @return string
     *
     * @throws IndexException When the index is invalid
     * @throws AssertionException When index is not an integer
     */
    public function offsetGet($index): string;

    /**
     * Checks if an index is valid
     *
     * @param int $index The index
     *
     * @return bool
     *
     * @throws AssertionException When index is not an integer
     */
    public function offsetExists($index): bool;

    /**
     * Not implemented
     *
     * @param int $index The index
     *
     * @return void
     *
     * @throws ImmutableException When called
     */
    public function offsetUnset($index): void;

    /**
     * Retrieves a list of characters
     *
     * @return Sequence
     */
    public function chars(): Sequence;

    /**
     * Checks if this string contains a search string
     *
     * @param string $search        The search string
     * @param bool   $caseSensitive Whether search should be case sensitive
     *
     * @return bool
     */
    public function contains(string $search, bool $caseSensitive = true): bool;

    /**
     * Checks if this string starts with a search string
     *
     * @param string $search        The search string
     * @param bool   $caseSensitive Whether search should be case sensitive
     *
     * @return bool
     */
    public function startsWith(string $search, bool $caseSensitive = true): bool;

    /**
     * Checks if this string ends with a search string
     *
     * @param string $search        The search string
     * @param bool   $caseSensitive Whether search should be case sensitive
     *
     * @return bool
     */
    public function endsWith(string $search, bool $caseSensitive = true): bool;

    /**
     * Retrieves the first index of a search string
     *
     * Returns -1 if the search string is not found.
     *
     * @param string   $search        The search string
     * @param int|null $start         The start index or null for entire string
     * @param bool     $caseSensitive Whether search should be case sensitive
     *
     * @return int
     *
     * @throws DomainException When the start index is invalid
     */
    public function indexOf(string $search, ?int $start = null, bool $caseSensitive = true): int;

    /**
     * Retrieves the last index of a search string
     *
     * Returns -1 if the search string is not found.
     *
     * @param string   $search        The search string
     * @param int|null $stop          The stop index or null for entire string
     * @param bool     $caseSensitive Whether search should be case sensitive
     *
     * @return int
     *
     * @throws DomainException When the stop index is invalid
     */
    public function lastIndexOf(string $search, ?int $stop = null, bool $caseSensitive = true): int;

    /**
     * Creates a string with the given string appended
     *
     * @param string $string The string
     *
     * @return StringLiteral
     */
    public function append(string $string): StringLiteral;

    /**
     * Creates a string with the given string prepended
     *
     * @param string $string The string
     *
     * @return StringLiteral
     */
    public function prepend(string $string): StringLiteral;

    /**
     * Creates a string with the given string inserted at a given index
     *
     * @param int    $index  The index
     * @param string $string The string
     *
     * @return StringLiteral
     *
     * @throws DomainException When the index is out of bounds
     */
    public function insert(int $index, string $string): StringLiteral;

    /**
     * Creates a string that is wrapped with a given string
     *
     * @param string $string The string
     *
     * @return StringLiteral
     */
    public function surround(string $string): StringLiteral;

    /**
     * Creates a string centered and padded to a given length
     *
     * The pad is a single character string used to pad the string.
     *
     * @param int         $length The desired string length
     * @param string|null $char   The padding character or null for a space
     *
     * @return StringLiteral
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the padding character is invalid
     */
    public function pad(int $length, ?string $char = null): StringLiteral;

    /**
     * Creates a string padded on the left to a given length
     *
     * The pad is a single character string used to pad the string.
     *
     * @param int         $length The desired string length
     * @param string|null $char   The padding character or null for a space
     *
     * @return StringLiteral
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the padding character is invalid
     */
    public function padLeft(int $length, ?string $char = null): StringLiteral;

    /**
     * Creates a string padded on the right to a given length
     *
     * The pad is a single character string used to pad the string.
     *
     * @param int         $length The desired string length
     * @param string|null $char   The padding character or null for a space
     *
     * @return StringLiteral
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the padding character is invalid
     */
    public function padRight(int $length, ?string $char = null): StringLiteral;

    /**
     * Creates a string truncated to a given length
     *
     * If a substring is provided, it is appended to the end of the string.
     *
     * If truncating occurs, the string is further truncated and the substring
     * is appended without exceeding the desired length.
     *
     * @param int    $length The desired string length
     * @param string $append A string to append
     *
     * @return StringLiteral
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the append string is invalid
     */
    public function truncate(int $length, string $append = ''): StringLiteral;

    /**
     * Creates a string truncated to a given length without splitting words
     *
     * If a substring is provided, it is appended to the end of the string.
     *
     * If truncating occurs, the string is further truncated and the substring
     * is appended without exceeding the desired length.
     *
     * @param int    $length The desired string length
     * @param string $append A string to append
     *
     * @return StringLiteral
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the append string is invalid
     */
    public function truncateWords(int $length, string $append = ''): StringLiteral;

    /**
     * Creates a string that repeats the original string
     *
     * @param int $multiplier Times to repeat; must be greater than zero
     *
     * @return StringLiteral
     *
     * @throws DomainException When the multiplier is invalid
     */
    public function repeat(int $multiplier): StringLiteral;

    /**
     * Creates a substring between two indexes
     *
     * @param int      $start The start index
     * @param int|null $stop  The stop index or null for the remainder
     *
     * @return StringLiteral
     *
     * @throws DomainException When the start index is invalid
     * @throws DomainException When the stop index is invalid
     */
    public function slice(int $start, ?int $stop = null): StringLiteral;

    /**
     * Creates a substring starting at an index
     *
     * @param int      $start  The start index
     * @param int|null $length The length or null for the remainder
     *
     * @return StringLiteral
     *
     * @throws DomainException When the start index is invalid
     * @throws DomainException When the string length is invalid
     */
    public function substr(int $start, ?int $length = null): StringLiteral;

    /**
     * Creates a list of strings split by a delimiter
     *
     * @param string   $delimiter The delimiter
     * @param int|null $limit     The limit or null for no limit
     *
     * @return Sequence
     *
     * @throws DomainException When the delimiter is empty
     */
    public function split(string $delimiter = ' ', ?int $limit = null): Sequence;

    /**
     * Creates a list of string chunks
     *
     * Each string in the list is represented by a StringLiteral instance.
     *
     * @param int $size The chunk size; must be greater than zero
     *
     * @return Sequence
     *
     * @throws DomainException When the chunk size is invalid
     */
    public function chunk(int $size = 1): Sequence;

    /**
     * Creates a string replacing all occurrences of search with replacement
     *
     * If search and replacement are arrays, then a value is used from each
     * array to search and replace on subject.
     *
     * If replacement has fewer values than search, then an empty string is
     * used for the rest of replacement values.
     *
     * If search is an array and replacement is a string, then the replacement
     * string is used for every value of search.
     *
     * @param string|array $search  The search string or array
     * @param string|array $replace The replacement string or array
     *
     * @return StringLiteral
     */
    public function replace($search, $replace): StringLiteral;

    /**
     * Creates a string with both ends trimmed
     *
     * @param string|null $mask Characters to trim or null to trim whitespace
     *
     * @return StringLiteral
     */
    public function trim(?string $mask = null): StringLiteral;

    /**
     * Creates a string with the left end trimmed
     *
     * @param string|null $mask Characters to trim or null to trim whitespace
     *
     * @return StringLiteral
     */
    public function trimLeft(?string $mask = null): StringLiteral;

    /**
     * Creates a string with the right end trimmed
     *
     * @param string|null $mask Characters to trim or null to trim whitespace
     *
     * @return StringLiteral
     */
    public function trimRight(?string $mask = null): StringLiteral;

    /**
     * Creates a string with tabs replaced by spaces
     *
     * @param int $tabsize Number of spaces for each tab; cannot be negative
     *
     * @return StringLiteral
     *
     * @throws DomainException When the tabsize is invalid
     */
    public function expandTabs(int $tabsize = 4): StringLiteral;

    /**
     * Creates a lower-case string
     *
     * @return StringLiteral
     */
    public function toLowerCase(): StringLiteral;

    /**
     * Creates an upper-case string
     *
     * @return StringLiteral
     */
    public function toUpperCase(): StringLiteral;

    /**
     * Creates a string with the first character lower-case
     *
     * @return StringLiteral
     */
    public function toFirstLowerCase(): StringLiteral;

    /**
     * Creates a string with the first character upper-case
     *
     * @return StringLiteral
     */
    public function toFirstUpperCase(): StringLiteral;

    /**
     * Creates a camel-case string
     *
     * Trims surrounding spaces and capitalizes letters following digits,
     * spaces, dashes and underscores.
     *
     * The first letter is lowercase and spaces, dashes, and underscores are
     * removed.
     *
     * @return StringLiteral
     */
    public function toCamelCase(): StringLiteral;

    /**
     * Creates a pascal-case string
     *
     * Trims surrounding spaces and capitalizes letters following digits,
     * spaces, dashes and underscores.
     *
     * The first letter is capitalized and spaces, dashes, and underscores are
     * removed.
     *
     * @return StringLiteral
     */
    public function toPascalCase(): StringLiteral;

    /**
     * Creates a snake-case string
     *
     * Semantic alias for toLowerUnderscored.
     *
     * Trims surrounding spaces and adds an underscore before uppercase
     * characters (except the first character).
     *
     * Underscores are added in place of spaces and hyphens, and the string is
     * converted to lowercase.
     *
     * @return StringLiteral
     */
    public function toSnakeCase(): StringLiteral;

    /**
     * Creates a hyphenated lowercase string
     *
     * Trims surrounding spaces and adds a hyphen before uppercase characters
     * (except the first character).
     *
     * Hyphens are added in place of spaces and underscores, and the string is
     * converted to lowercase.
     *
     * @return StringLiteral
     */
    public function toLowerHyphenated(): StringLiteral;

    /**
     * Creates a hyphenated uppercase string
     *
     * Trims surrounding spaces and adds a hyphen before uppercase characters
     * (except the first character).
     *
     * Hyphens are added in place of spaces and underscores, and the string is
     * converted to uppercase.
     *
     * @return StringLiteral
     */
    public function toUpperHyphenated(): StringLiteral;

    /**
     * Creates an underscored lowercase string
     *
     * Trims surrounding spaces and adds an underscore before uppercase
     * characters (except the first character).
     *
     * Underscores are added in place of spaces and hyphens, and the string is
     * converted to lowercase.
     *
     * @return StringLiteral
     */
    public function toLowerUnderscored(): StringLiteral;

    /**
     * Creates an underscored uppercase string
     *
     * Trims surrounding spaces and adds an underscore before uppercase
     * characters (except the first character).
     *
     * Underscores are added in place of spaces and hyphens, and the string is
     * converted to uppercase.
     *
     * @return StringLiteral
     */
    public function toUpperUnderscored(): StringLiteral;

    /**
     * Creates a string that is suitable for a URL segment
     *
     * Attempts to convert the string to ASCII characters and replaces non-word
     * characters with hyphens.
     *
     * Duplicate hyphens are removed and the string is converted to lowercase.
     *
     * @return StringLiteral
     */
    public function toSlug(): StringLiteral;

    /**
     * Retrieves an iterator for characters
     *
     * @return Traversable
     */
    public function getIterator(): Traversable;
}
