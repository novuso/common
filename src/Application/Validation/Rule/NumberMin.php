<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class NumberMin
 */
class NumberMin extends CompositeSpecification
{
    /**
     * Minimum number
     *
     * @var int|float
     */
    protected $minNumber;

    /**
     * Constructs NumberMin
     *
     * @param int|float $minNumber The minimum number
     */
    public function __construct($minNumber)
    {
        $this->minNumber = $minNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::minNumber($candidate, $this->minNumber);
    }
}
