<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class NumberExact
 */
class NumberExact extends CompositeSpecification
{
    /**
     * Exact number
     *
     * @var int|float
     */
    protected $number;

    /**
     * Constructs NumberExact
     *
     * @param int|float $number The number
     */
    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::exactNumber($candidate, $this->number);
    }
}
