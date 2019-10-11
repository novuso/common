<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsIpV6Address
 */
class IsIpV6Address extends CompositeSpecification
{
    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::isIpV6Address($candidate);
    }
}
