<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\DateTime;

use Novuso\System\Type\Enum;

/**
 * Class WeekDay
 *
 * @method static SUNDAY
 * @method static MONDAY
 * @method static TUESDAY
 * @method static WEDNESDAY
 * @method static THURSDAY
 * @method static FRIDAY
 * @method static SATURDAY
 */
final class WeekDay extends Enum
{
    /**
     * Sunday
     *
     * @var int
     */
    public const SUNDAY = 0;

    /**
     * Monday
     *
     * @var int
     */
    public const MONDAY = 1;

    /**
     * Tuesday
     *
     * @var int
     */
    public const TUESDAY = 2;

    /**
     * Wednesday
     *
     * @var int
     */
    public const WEDNESDAY = 3;

    /**
     * Thursday
     *
     * @var int
     */
    public const THURSDAY = 4;

    /**
     * Friday
     *
     * @var int
     */
    public const FRIDAY = 5;

    /**
     * Saturday
     *
     * @var int
     */
    public const SATURDAY = 6;
}
