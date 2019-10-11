<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class KeyNotEmpty
 */
class KeyNotEmpty extends CompositeSpecification
{
    /**
     * Key
     *
     * @var string
     */
    protected $key;

    /**
     * Constructs KeyNotEmpty
     *
     * @param string $key The key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return Validate::keyNotEmpty($candidate, $this->key);
    }
}
