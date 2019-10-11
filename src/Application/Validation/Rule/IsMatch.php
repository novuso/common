<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class IsMatch
 */
class IsMatch extends CompositeSpecification
{
    /**
     * Regex pattern
     *
     * @var string
     */
    protected $pattern;

    /**
     * Constructs IsMatch
     *
     * @param string $pattern The regex pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::isMatch($candidate, $this->pattern);
    }
}
