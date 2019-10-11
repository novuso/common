<?php declare(strict_types=1);

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
    /**
     * Input data
     *
     * @var InputData
     */
    protected $input;

    /**
     * Errors
     *
     * @var HashTable
     */
    protected $errors;

    /**
     * Constructs ValidationContext
     *
     * @param InputData $input The input data
     */
    public function __construct(InputData $input)
    {
        $this->input = $input;
        $this->errors = HashTable::of('string', HashSet::class);
    }

    /**
     * Retrieves a value by field name
     *
     * @param string $name The field name
     *
     * @return mixed
     *
     * @throws KeyException
     */
    public function get(string $name)
    {
        return $this->input->get($name);
    }

    /**
     * Checks if there are errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !$this->errors->isEmpty();
    }

    /**
     * Adds an error
     *
     * @param string $name    The field name
     * @param string $message The error message
     *
     * @return void
     */
    public function addError(string $name, string $message)
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
     *
     * @return array
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
