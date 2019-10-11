<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\Communication;

use Novuso\Common\Domain\Type\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Validate;

/**
 * Class EmailAddress
 */
final class EmailAddress extends ValueObject
{
    /**
     * Email address value
     *
     * @var string
     */
    protected $value;

    /**
     * Constructs EmailAddress
     *
     * @param string $value The email address
     *
     * @throws DomainException When the email address is invalid
     */
    public function __construct(string $value)
    {
        if (!Validate::isEmail($value)) {
            $message = sprintf('Invalid email address: %s', $value);
            throw new DomainException($message);
        }

        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): EmailAddress
    {
        return new static($value);
    }

    /**
     * Retrieves the local part
     *
     * @return string
     */
    public function localPart(): string
    {
        $parts = explode('@', $this->value);

        return $parts[0];
    }

    /**
     * Retrieves the domain part
     *
     * @return string
     */
    public function domainPart(): string
    {
        $parts = explode('@', $this->value);
        $domain = trim($parts[1], '[]');

        return $domain;
    }

    /**
     * Retrieves the unique lowercase value
     *
     * @return string
     */
    public function canonical(): string
    {
        return strtolower($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->value;
    }
}
