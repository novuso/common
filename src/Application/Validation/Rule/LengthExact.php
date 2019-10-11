<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class LengthExact
 */
class LengthExact extends CompositeSpecification
{
    /**
     * Length
     *
     * @var int
     */
    protected $length;

    /**
     * Constructs LengthExact
     *
     * @param int $length The length
     */
    public function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::exactLength($candidate, $this->length);
    }
}
