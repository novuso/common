<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Validate;
use Novuso\System\Utility\VarPrinter;

/**
 * JsonObject is a wrapper for JSON encoded data
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class JsonObject extends ValueObject
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
    protected $encOptions;

    /**
     * Constructs JsonObject
     *
     * @param mixed    $data       The data to convert to JSON
     * @param int|null $encOptions Options bitmap for encoding
     *
     * @throws DomainException When the data is not json encodable
     */
    public function __construct($data, ?int $encOptions = null)
    {
        $json = json_encode($data);

        if ($json === false) {
            $message = sprintf('Unable to JSON encode: %s', VarPrinter::toString($data));
            throw new DomainException($message);
        }

        if ($encOptions === null) {
            $encOptions = JSON_UNESCAPED_SLASHES;
        }

        $this->data = $data;
        $this->encOptions = $encOptions;
    }

    /**
     * Creates instance from data
     *
     * @param mixed $data The data to convert to JSON
     *
     * @return JsonObject
     *
     * @throws DomainException When the data is not json encodable
     */
    public static function fromData($data): JsonObject
    {
        return new static($data);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): JsonObject
    {
        if (!Validate::isJson($value)) {
            $message = sprintf('Invalid JSON string: %s', $value);
            throw new DomainException($message);
        }

        return new static(json_decode($value, true));
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
        return json_encode($this->data, $this->encOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}
