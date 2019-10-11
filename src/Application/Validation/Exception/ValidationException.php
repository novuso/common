<?php declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Exception;

use Novuso\System\Exception\ErrorException;

/**
 * Class ValidationException
 */
class ValidationException extends ErrorException
{
    /**
     * Creates instance from validation errors
     *
     * @param array $errors The errors
     *
     * @return ValidationException
     */
    public static function fromErrors(array $errors): ValidationException
    {
        $message = 'Validation Failed';

        return new static($message, $errors);
    }
}
