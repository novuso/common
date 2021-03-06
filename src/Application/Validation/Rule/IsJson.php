<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsJson
 */
class IsJson extends CompositeSpecification
{
    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return Validate::isJson($candidate);
    }
}
