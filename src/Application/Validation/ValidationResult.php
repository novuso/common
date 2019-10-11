<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation;

use Novuso\Common\Application\Validation\Data\ApplicationData;
use Novuso\Common\Application\Validation\Data\ErrorData;
use Novuso\System\Exception\MethodCallException;

/**
 * Class ValidationResult
 */
final class ValidationResult
{
    /**
     * Passed state
     *
     * @var string
     */
    public const PASSED = 'passed';

    /**
     * Failed state
     *
     * @var string
     */
    public const FAILED = 'failed';

    /**
     * Validation state
     *
     * @var string
     */
    protected $state;

    /**
     * Application data
     *
     * @var ApplicationData|null
     */
    protected $applicationData;

    /**
     * Error data
     *
     * @var ErrorData|null
     */
    protected $errorData;

    /**
     * Constructs ValidationResult
     *
     * @param string $state The validation state
     */
    protected function __construct(string $state)
    {
        $this->state = $state;
    }

    /**
     * Creates ValidationResult for passed state
     *
     * @param ApplicationData $applicationData The application data
     *
     * @return ValidationResult
     */
    public static function passed(ApplicationData $applicationData): ValidationResult
    {
        $result = new static(static::PASSED);
        $result->applicationData = $applicationData;

        return $result;
    }

    /**
     * Creates ValidationResult for failed state
     *
     * @param ErrorData $errorData The error data
     *
     * @return static
     */
    public static function failed(ErrorData $errorData): ValidationResult
    {
        $result = new static(static::FAILED);
        $result->errorData = $errorData;

        return $result;
    }

    /**
     * Checks if validation passed
     *
     * @return bool
     */
    public function isPassed(): bool
    {
        return $this->state === static::PASSED;
    }

    /**
     * Checks if validation failed
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->state === static::FAILED;
    }

    /**
     * Retrieves the validated application data
     *
     * @return ApplicationData
     *
     * @throws MethodCallException When the validation state is not passed
     */
    public function getData(): ApplicationData
    {
        if ($this->state !== static::PASSED) {
            throw new MethodCallException('Data not available for the current state');
        }

        return $this->applicationData;
    }

    /**
     * Retrieves the validation error data
     *
     * @return ErrorData
     *
     * @throws MethodCallException When the validation state is not failed
     */
    public function getErrors(): ErrorData
    {
        if ($this->state !== static::FAILED) {
            throw new MethodCallException('Errors not available for the current state');
        }

        return $this->errorData;
    }
}
