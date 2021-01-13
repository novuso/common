<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

use Novuso\Common\Application\Validation\Data\ApplicationData;
use Novuso\Common\Application\Validation\Data\ErrorData;
use Novuso\System\Exception\MethodCallException;

/**
 * Class ValidationResult
 */
final class ValidationResult
{
    public const PASSED = 'passed';
    public const FAILED = 'failed';

    protected ?ApplicationData $applicationData = null;
    protected ?ErrorData $errorData = null;

    /**
     * Constructs ValidationResult
     */
    protected function __construct(protected string $state)
    {
    }

    /**
     * Creates ValidationResult for passed state
     */
    public static function passed(ApplicationData $applicationData): static
    {
        $result = new static(static::PASSED);
        $result->applicationData = $applicationData;

        return $result;
    }

    /**
     * Creates ValidationResult for failed state
     */
    public static function failed(ErrorData $errorData): static
    {
        $result = new static(static::FAILED);
        $result->errorData = $errorData;

        return $result;
    }

    /**
     * Checks if validation passed
     */
    public function isPassed(): bool
    {
        return $this->state === static::PASSED;
    }

    /**
     * Checks if validation failed
     */
    public function isFailed(): bool
    {
        return $this->state === static::FAILED;
    }

    /**
     * Retrieves the validated application data
     *
     * @throws MethodCallException When the validation state is not passed
     */
    public function getData(): ApplicationData
    {
        if ($this->state !== static::PASSED) {
            throw new MethodCallException(
                'Data not available for the current state'
            );
        }

        return $this->applicationData;
    }

    /**
     * Retrieves the validation error data
     *
     * @throws MethodCallException When the validation state is not failed
     */
    public function getErrors(): ErrorData
    {
        if ($this->state !== static::FAILED) {
            throw new MethodCallException(
                'Errors not available for the current state'
            );
        }

        return $this->errorData;
    }
}
