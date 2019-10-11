<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class CountExact
 */
class CountExact extends CompositeSpecification
{
    /**
     * Count
     *
     * @var int
     */
    protected $count;

    /**
     * Constructs CountExact
     *
     * @param int $count The exact count
     */
    public function __construct(int $count)
    {
        $this->count = $count;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::exactCount($candidate, $this->count);
    }
}
