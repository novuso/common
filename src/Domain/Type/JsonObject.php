<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Validate;
use Novuso\System\Utility\VarPrinter;

/**
 * Class JsonObject
 */
final class JsonObject extends ValueObject
{
    /**
     * Data
     *
     * @var mixed
     */
    protected $data;

    /**
     * Encoding options
     *
     * @var int
     */
    protected $encodingOptions;

    /**
     * Constructs JsonObject
     *
     * @param mixed    $data            The data to convert to JSON
     * @param int|null $encodingOptions Options bitmask for encoding
     *
     * @throws DomainException When the data is not JSON encodable
     */
    public function __construct($data, ?int $encodingOptions = null)
    {
        if (!Validate::isJsonEncodable($data)) {
            $message = sprintf('Unable to JSON encode: %s', VarPrinter::toString($data));
            throw new DomainException($message);
        }

        if ($encodingOptions === null) {
            $encodingOptions = JSON_UNESCAPED_SLASHES;
        }

        $this->data = $data;
        $this->encodingOptions = $encodingOptions;
    }

    /**
     * Creates instance from data
     *
     * @param mixed    $data            The data to convert to JSON
     * @param int|null $encodingOptions Options bitmask for encoding
     *
     * @return JsonObject
     *
     * @throws DomainException When the data is not JSON encodable
     */
    public static function fromData($data, ?int $encodingOptions = null): JsonObject
    {
        return new static($data, $encodingOptions);
    }

    /**
     * {@inheritDoc}
     */
    public static function fromString(string $value): JsonObject
    {
        if (!Validate::isJson($value)) {
            $message = sprintf('Invalid JSON string: %s', $value);
            throw new DomainException($message);
        }

        return new static(json_decode($value, $assoc = true));
    }

    /**
     * Retrieves data representation
     *
     * @return mixed
     */
    public function toData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return json_encode($this->data, $this->encodingOptions);
    }

    /**
     * Retrieves a pretty print representation
     *
     * @return string
     */
    public function prettyPrint(): string
    {
        return json_encode($this->data, $this->encodingOptions | JSON_PRETTY_PRINT);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}
