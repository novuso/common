<?php declare(strict_types=1);

namespace Novuso\Common\Application\Process;

use Novuso\System\Type\Enum;

/**
 * ProcessError represents the process runner error behavior
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ProcessError extends Enum
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
}
