<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class InList
 */
class InList extends CompositeSpecification
{
    /**
     * List
     *
     * @var array
     */
    protected $list;

    /**
     * Constructs InList
     *
     * @param array $list The list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::isOneOf($candidate, $this->list);
    }
}
