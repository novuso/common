<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class StringContains
 */
class StringContains extends CompositeSpecification
{
    /**
     * Search string
     *
     * @var string
     */
    protected $search;

    /**
     * Constructs StringContains
     *
     * @param string $search The search string
     */
    public function __construct(string $search)
    {
        $this->search = $search;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::contains($candidate, $this->search);
    }
}
