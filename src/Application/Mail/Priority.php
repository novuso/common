<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail;

use Novuso\System\Type\Enum;

/**
 * Priority represents a message priority level
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class Priority extends Enum
{
    /**
     * Highest priority
     *
     * @var int
     */
    const HIGHEST = 1;

    /**
     * High priority
     *
     * @var int
     */
    const HIGH = 2;

    /**
     * Normal priority
     *
     * @var int
     */
    const NORMAL = 3;

    /**
     * Low priority
     *
     * @var int
     */
    const LOW = 4;

    /**
     * Lowest priority
     *
     * @var int
     */
    const LOWEST = 5;
}
