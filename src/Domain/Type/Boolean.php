<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\System\Type\Enum;

/**
 * Boolean is a wrapper for the bool type
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class Boolean extends Enum
{
    /**
     * TRUE
     *
     * @var bool
     */
    public const TRUE = true;

    /**
     * FALSE
     *
     * @var bool
     */
    public const FALSE = false;
}
