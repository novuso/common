<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class LengthMax
 */
class LengthMax extends CompositeSpecification
{
    /**
     * Maximum length
     *
     * @var int
     */
    protected $maxLength;

    /**
     * Constructs LengthMax
     *
     * @param int $maxLength The maximum length
     */
    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::maxLength($candidate, $this->maxLength);
    }
}
