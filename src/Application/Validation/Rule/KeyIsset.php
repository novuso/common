<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Rule;

use Novuso\Common\Domain\Specification\CompositeSpecification;
use Novuso\System\Utility\Validate;

/**
 * Class KeyIsset
 */
class KeyIsset extends CompositeSpecification
{
    /**
     * Key
     *
     * @var string
     */
    protected $key;

    /**
     * Constructs KeyIsset
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
        return Validate::keyIsset($candidate, $this->key);
    }
}
