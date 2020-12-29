<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use JsonSerializable;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Serialization\Serializable;
use Novuso\System\Type\Arrayable;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;
use Novuso\System\Type\Type;

/**
 * Interface Message
 */
interface Message extends Arrayable, Comparable, Equatable, JsonSerializable, Serializable
{
    /**
     * Retrieves the ID
     */
    public function id(): MessageId;

    /**
     * Retrieves the type
     */
    public function type(): MessageType;

    /**
     * Retrieves the timestamp
     */
    public function timestamp(): DateTime;

    /**
     * Retrieves the payload
     */
    public function payload(): Payload;

    /**
     * Retrieves the payload type
     */
    public function payloadType(): Type;

    /**
     * Retrieves the meta data
     */
    public function metaData(): MetaData;

    /**
     * Creates instance with the given meta data
     */
    public function withMetaData(MetaData $metaData): static;

    /**
     * Creates instance after merging meta data
     */
    public function mergeMetaData(MetaData $metaData): static;

    /**
     * Retrieves a string representation
     */
    public function toString(): string;

    /**
     * Handles casting to a string
     */
    public function __toString(): string;

    /**
     * Retrieves an array representation
     */
    public function toArray(): array;

    /**
     * Retrieves a value for JSON encoding
     */
    public function jsonSerialize(): array;
}
