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
    public const SUNDAY = 0;
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;
}
