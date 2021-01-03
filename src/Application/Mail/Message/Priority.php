<?php

declare(strict_types=1);

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
    public const HIGHEST = 1;
    public const HIGH = 2;
    public const NORMAL = 3;
    public const LOW = 4;
    public const LOWEST = 5;
}
