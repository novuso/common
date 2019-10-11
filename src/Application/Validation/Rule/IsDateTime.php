<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use DateTimeImmutable;
use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Throwable;

/**
 * Class IsDateTime
 */
class IsDateTime extends CompositeSpecification
{
    /**
     * Date/time format
     *
     * @var string
     */
    protected $format;

    /**
     * Constructs IsDateTime
     *
     * @param string $format The format
     */
    public function __construct(string $format)
    {
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        try {
            if ($this->format === DateTime::STRING_FORMAT) {
                $dateTime = DateTime::fromString($candidate);
            } else {
                $dateTime = DateTimeImmutable::createFromFormat($this->format, $candidate);
                if ($dateTime === false) {
                    return false;
                }
            }

            return $dateTime->format($this->format) === $candidate;
        } catch (Throwable $e) {
            return false;
        }
    }
}
