<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

use Novuso\Common\Application\Validation\Data\InputData;
use Novuso\System\Collection\HashSet;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;

/**
 * Class ValidationContext
 */
class ValidationContext
{
    protected HashTable $errors;

    /**
     * Constructs ValidationContext
     */
    public function __construct(protected InputData $input)
    {
        $this->errors = HashTable::of('string', HashSet::class);
    }

    /**
     * Retrieves a value by field name
     *
     * @throws KeyException
     */
    public function get(string $name): mixed
    {
        return $this->input->get($name);
    }

    /**
     * Checks if there are errors
     */
    public function hasErrors(): bool
    {
        return !$this->errors->isEmpty();
    }

    /**
     * Adds an error
     */
    public function addError(string $name, string $message): void
    {
        if (!$this->errors->has($name)) {
            $this->errors->set($name, HashSet::of('string'));
        }

        /** @var HashSet $messages */
        $messages = $this->errors->get($name);
        $messages->add($message);
    }

    /**
     * Retrieves the collection of errors
     */
    public function getErrors(): array
    {
        $errors = [];

        /** @var string $name @var HashSet $messages */
        foreach ($this->errors as $name => $messages) {
            $errors[$name] = [];
            foreach ($messages as $message) {
                $errors[$name][] = $message;
            }
        }

        return $errors;
    }
}
