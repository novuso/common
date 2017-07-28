<?php declare(strict_types=1);

namespace Novuso\Common\Application\Process;

use Novuso\System\Type\Enum;

/**
 * ProcessErrorBehavior represents the process runner error behavior
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ProcessErrorBehavior extends Enum
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
