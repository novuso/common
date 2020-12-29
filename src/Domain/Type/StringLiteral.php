<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Novuso\System\Collection\ArrayList;
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
     */
    public function value(): string;

    /**
     * Retrieves the string length
     */
    public function length(): int;

    /**
     * Checks if empty
     */
    public function isEmpty(): bool;

    /**
     * Retrieves the character count
     */
    public function count(): int;

    /**
     * Retrieves the character at an index
     *
     * @throws IndexException When the index is invalid
     */
    public function get(int $index): string;

    /**
     * Checks if an index is valid
     */
    public function has(int $index): bool;

    /**
     * Not implemented
     *
     * @throws ImmutableException When called
     */
    public function offsetSet(mixed $index, mixed $character): void;

    /**
     * Retrieves the character at an index
     *
     * @throws IndexException When the index is invalid
     * @throws AssertionException When index is not an integer
     */
    public function offsetGet(mixed $index): string;

    /**
     * Checks if an index is valid
     *
     * @throws AssertionException When index is not an integer
     */
    public function offsetExists(mixed $index): bool;

    /**
     * Not implemented
     *
     * @throws ImmutableException When called
     */
    public function offsetUnset(mixed $index): void;

    /**
     * Retrieves a list of characters
     */
    public function chars(): ArrayList;

    /**
     * Checks if this string contains a search string
     */
    public function contains(string $search, bool $caseSensitive = true): bool;

    /**
     * Checks if this string starts with a search string
     */
    public function startsWith(string $search, bool $caseSensitive = true): bool;

    /**
     * Checks if this string ends with a search string
     */
    public function endsWith(string $search, bool $caseSensitive = true): bool;

    /**
     * Retrieves the first index of a search string
     *
     * Returns -1 if the search string is not found.
     *
     * @throws DomainException When the start index is invalid
     */
    public function indexOf(string $search, ?int $start = null, bool $caseSensitive = true): int;

    /**
     * Retrieves the last index of a search string
     *
     * Returns -1 if the search string is not found.
     *
     * @throws DomainException When the stop index is invalid
     */
    public function lastIndexOf(string $search, ?int $stop = null, bool $caseSensitive = true): int;

    /**
     * Creates a string with the given string appended
     */
    public function append(string $string): static;

    /**
     * Creates a string with the given string prepended
     */
    public function prepend(string $string): static;

    /**
     * Creates a string with the given string inserted at a given index
     *
     * @throws DomainException When the index is out of bounds
     */
    public function insert(int $index, string $string): static;

    /**
     * Creates a string that is wrapped with a given string
     */
    public function surround(string $string): static;

    /**
     * Creates a string centered and padded to a given length
     *
     * The pad is a single character string used to pad the string.
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the padding character is invalid
     */
    public function pad(int $length, ?string $char = null): static;

    /**
     * Creates a string padded on the left to a given length
     *
     * The pad is a single character string used to pad the string.
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the padding character is invalid
     */
    public function padLeft(int $length, ?string $char = null): static;

    /**
     * Creates a string padded on the right to a given length
     *
     * The pad is a single character string used to pad the string.
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the padding character is invalid
     */
    public function padRight(int $length, ?string $char = null): static;

    /**
     * Creates a string truncated to a given length
     *
     * If a substring is provided, it is appended to the end of the string.
     *
     * If truncating occurs, the string is further truncated and the substring
     * is appended without exceeding the desired length.
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the append string is invalid
     */
    public function truncate(int $length, string $append = ''): static;

    /**
     * Creates a string truncated to a given length without splitting words
     *
     * If a substring is provided, it is appended to the end of the string.
     *
     * If truncating occurs, the string is further truncated and the substring
     * is appended without exceeding the desired length.
     *
     * @throws DomainException When the string length is invalid
     * @throws DomainException When the append string is invalid
     */
    public function truncateWords(int $length, string $append = ''): static;

    /**
     * Creates a string that repeats the original string
     *
     * @throws DomainException When the multiplier is invalid
     */
    public function repeat(int $multiplier): static;

    /**
     * Creates a substring between two indexes
     *
     * @throws DomainException When the start index is invalid
     * @throws DomainException When the stop index is invalid
     */
    public function slice(int $start, ?int $stop = null): static;

    /**
     * Creates a substring starting at an index
     *
     * @throws DomainException When the start index is invalid
     * @throws DomainException When the string length is invalid
     */
    public function substr(int $start, ?int $length = null): static;

    /**
     * Creates a list of strings split by a delimiter
     *
     * @throws DomainException When the delimiter is empty
     */
    public function split(string $delimiter = ' ', ?int $limit = null): ArrayList;

    /**
     * Creates a list of string chunks
     *
     * Each string in the list is represented by a static instance.
     *
     * @throws DomainException When the chunk size is invalid
     */
    public function chunk(int $size = 1): ArrayList;

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
     */
    public function replace(string|array $search, string|array $replace): static;

    /**
     * Creates a string with both ends trimmed
     */
    public function trim(?string $mask = null): static;

    /**
     * Creates a string with the left end trimmed
     */
    public function trimLeft(?string $mask = null): static;

    /**
     * Creates a string with the right end trimmed
     */
    public function trimRight(?string $mask = null): static;

    /**
     * Creates a string with tabs replaced by spaces
     *
     * @throws DomainException When the tab size is invalid
     */
    public function expandTabs(int $tabSize = 4): static;

    /**
     * Creates a lower-case string
     */
    public function toLowerCase(): static;

    /**
     * Creates an upper-case string
     */
    public function toUpperCase(): static;

    /**
     * Creates a string with the first character lower-case
     */
    public function toFirstLowerCase(): static;

    /**
     * Creates a string with the first character upper-case
     */
    public function toFirstUpperCase(): static;

    /**
     * Creates a camel-case string
     *
     * Trims surrounding spaces and capitalizes letters following digits,
     * spaces, dashes and underscores.
     *
     * The first letter is lowercase and spaces, dashes, and underscores are
     * removed.
     */
    public function toCamelCase(): static;

    /**
     * Creates a pascal-case string
     *
     * Trims surrounding spaces and capitalizes letters following digits,
     * spaces, dashes and underscores.
     *
     * The first letter is capitalized and spaces, dashes, and underscores are
     * removed.
     */
    public function toPascalCase(): static;

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
     */
    public function toSnakeCase(): static;

    /**
     * Creates a hyphenated lowercase string
     *
     * Trims surrounding spaces and adds a hyphen before uppercase characters
     * (except the first character).
     *
     * Hyphens are added in place of spaces and underscores, and the string is
     * converted to lowercase.
     */
    public function toLowerHyphenated(): static;

    /**
     * Creates a hyphenated uppercase string
     *
     * Trims surrounding spaces and adds a hyphen before uppercase characters
     * (except the first character).
     *
     * Hyphens are added in place of spaces and underscores, and the string is
     * converted to uppercase.
     */
    public function toUpperHyphenated(): static;

    /**
     * Creates an underscored lowercase string
     *
     * Trims surrounding spaces and adds an underscore before uppercase
     * characters (except the first character).
     *
     * Underscores are added in place of spaces and hyphens, and the string is
     * converted to lowercase.
     */
    public function toLowerUnderscored(): static;

    /**
     * Creates an underscored uppercase string
     *
     * Trims surrounding spaces and adds an underscore before uppercase
     * characters (except the first character).
     *
     * Underscores are added in place of spaces and hyphens, and the string is
     * converted to uppercase.
     */
    public function toUpperUnderscored(): static;

    /**
     * Creates a string that is suitable for a URL segment
     *
     * Attempts to convert the string to ASCII characters and replaces non-word
     * characters with hyphens.
     *
     * Duplicate hyphens are removed and the string is converted to lowercase.
     */
    public function toSlug(): static;

    /**
     * Retrieves an iterator for characters
     */
    public function getIterator(): Traversable;
}
