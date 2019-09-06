<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use JsonSerializable;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Equatable;

/**
 * Interface Value
 *
 * Implementations must adhere to value characteristics:
 *
 * * It is maintained as immutable
 * * It measures, quantifies, or describes a thing in the domain
 * * It models a conceptual whole by composing related attributes as a unit
 * * It is completely replaceable when the measurement or description changes
 * * It can be compared with others using value equality
 * * It supplies its collaborators with side-effect-free behavior
 */
interface Value extends Equatable, JsonSerializable
{
    /**
     * Creates instance from a string representation
     *
     * @param string $value The string representation
     *
     * @return static
     *
     * @throws DomainException When value is not valid
     */
    public static function fromString(string $value);

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString();

    /**
     * Retrieves value for JSON encoding
     *
     * @return mixed
     */
    public function jsonSerialize();

    /**
     * Checks if an object equals this instance
     *
     * The passed object must be an instance of the same type.
     *
     * The method should return false for invalid object types, rather than
     * throw an exception.
     *
     * @param mixed $object The object
     *
     * @return bool
     */
    public function equals($object): bool;

    /**
     * Retrieves a string representation for hashing
     *
     * The returned value must behave in a way consistent with the same
     * object's equals() method.
     *
     * A given object must consistently report the same hash value (unless it
     * is changed so that the new version is no longer considered "equal" to
     * the old), and two objects which equals() says are equal must report the
     * same hash value.
     *
     * @return string
     */
    public function hashValue(): string;
}
