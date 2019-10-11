<?php declare(strict_types=1);

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
     *
     * @var int
     */
    public const EXCEPTION = 1;

    /**
     * Ignore process errors
     *
     * @var int
     */
    public const IGNORE = 2;

    /**
     * Retry failed processes
     *
     * @var int
     */
    public const RETRY = 3;
}
