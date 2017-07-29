<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\System\Type\Enum;

/**
 * BoolObject is a wrapper for a boolean
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class BoolObject extends Enum
{
    /**
     * Boolean true
     *
     * @var bool
     */
    public const TRUE = true;

    /**
     * Boolean false
     *
     * @var bool
     */
    public const FALSE = false;
}
