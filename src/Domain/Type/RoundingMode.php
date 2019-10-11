<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

use Novuso\System\Type\Enum;

/**
 * Class RoundingMode
 *
 * @method static HALF_UP
 * @method static HALF_DOWN
 * @method static HALF_EVEN
 * @method static HALF_ODD
 */
final class RoundingMode extends Enum
{
    /**
     * Round up when half way
     *
     * @var int
     */
    public const HALF_UP = PHP_ROUND_HALF_UP;

    /**
     * Round down when half way
     *
     * @var int
     */
    public const HALF_DOWN = PHP_ROUND_HALF_DOWN;

    /**
     * Round towards the next even value
     *
     * @var int
     */
    public const HALF_EVEN = PHP_ROUND_HALF_EVEN;

    /**
     * Round towards the next odd value
     *
     * @var int
     */
    public const HALF_ODD = PHP_ROUND_HALF_ODD;
}
