<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use DateTimeZone;
use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsTimezone
 */
class IsTimezone extends CompositeSpecification
{
    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        if ($candidate instanceof DateTimeZone) {
            $candidate = $candidate->getName();
        }

        return Validate::isTimezone($candidate);
    }
}
