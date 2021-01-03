<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Process;

use Novuso\System\Type\Enum;

/**
 * Class ProcessErrorBehavior
 *
 * @method static EXCEPTION
 * @method static IGNORE
 * @method static RETRY
 */
final class ProcessErrorBehavior extends Enum
{
    /**
     * Throw exception on error
     */
    public const EXCEPTION = 1;

    /**
     * Ignore process errors
     */
    public const IGNORE = 2;

    /**
     * Retry failed processes
     */
    public const RETRY = 3;
}
