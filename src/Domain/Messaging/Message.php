<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use JsonSerializable;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\Serializable as Storable;
use Novuso\System\Type\Arrayable;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;
use Novuso\System\Type\Type;
use Serializable;

/**
 * Message is the interface for a domain message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Message extends Arrayable, Comparable, Equatable, JsonSerializable, Storable, Serializable
{
    /**
     * Creates instance from a serialized representation
     *
     * @param array $data The serialized representation
     *
     * @return Message
     *
     * @throws DomainException When the data is not valid
     */
    public static function arrayDeserialize(array $data);

    /**
     * Retrieves the ID
     *
     * @return MessageId
     */
    public function id(): MessageId;

    /**
     * Retrieves the type
     *
     * @return MessageType
     */
    public function type(): MessageType;

    /**
     * Retrieves the timestamp
     *
     * @return DateTime
     */
    public function timestamp(): DateTime;

    /**
     * Retrieves the payload
     *
     * @return Payload
     */
    public function payload(): Payload;

    /**
     * Retrieves the payload type
     *
     * @return Type
     */
    public function payloadType(): Type;

    /**
     * Retrieves the meta data
     *
     * @return MetaData
     */
    public function metaData(): MetaData;

    /**
     * Creates instance with the given meta data
     *
     * @param MetaData $metaData The meta data
     *
     * @return Message
     */
    public function withMetaData(MetaData $metaData);

    /**
     * Creates instance after merging meta data
     *
     * @param MetaData $metaData The meta data
     *
     * @return Message
     */
    public function mergeMetaData(MetaData $metaData);

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
    public function __toString(): string;

    /**
     * Retrieves an array representation
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Retrieves a value for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize(): array;

    /**
     * Retrieves a serialized representation
     *
     * @return array
     */
    public function arraySerialize(): array;

    /**
     * Retrieves a serialized representation
     *
     * @return string
     */
    public function serialize(): string;

    /**
     * Handles construction from a serialized representation
     *
     * @param string $serialized The serialized representation
     *
     * @return void
     */
    public function unserialize($serialized): void;

    /**
     * Compares to another object
     *
     * The passed object must be an instance of the same type.
     *
     * The method should return 0 for values considered equal, return -1 if
     * this instance is less than the passed value, and return 1 if this
     * instance is greater than the passed value.
     *
     * @param mixed $object The object
     *
     * @return int
     */
    public function compareTo($object): int;

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
