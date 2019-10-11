<?php declare(strict_types=1);

namespace Novuso\Common\Application\Mail\Message;

use Novuso\System\Type\Enum;

/**
 * Class Priority
 *
 * @method static HIGHEST
 * @method static HIGH
 * @method static NORMAL
 * @method static LOW
 * @method static LOWEST
 */
final class Priority extends Enum
{
    /**
     * Highest priority
     *
     * @var int
     */
    public const HIGHEST = 1;

    /**
     * High priority
     *
     * @var int
     */
    public const HIGH = 2;

    /**
     * Normal priority
     *
     * @var int
     */
    public const NORMAL = 3;

    /**
     * Low priority
     *
     * @var int
     */
    public const LOW = 4;

    /**
     * Lowest priority
     *
     * @var int
     */
    public const LOWEST = 5;
}
