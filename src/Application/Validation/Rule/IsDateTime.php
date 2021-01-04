<?php

declare(strict_types=1);

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
     * Constructs IsDateTime
     */
    public function __construct(protected string $format)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        try {
            if ($this->format === DateTime::STRING_FORMAT) {
                $dateTime = DateTime::fromString($candidate);
            } else {
                $dateTime = DateTimeImmutable::createFromFormat(
                    $this->format,
                    $candidate
                );
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
