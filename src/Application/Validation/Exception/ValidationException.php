<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Validation\Exception;

use Novuso\System\Exception\ErrorException;

/**
 * Class ValidationException
 */
class ValidationException extends ErrorException
{
    /**
     * Creates instance from validation errors
     */
    public static function fromErrors(array $errors): static
    {
        $message = 'Validation Failed';

        return new static($message, $errors);
    }
}
