<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use Novuso\System\Type\Enum;

/**
 * Class MessageType
 *
 * @method static EVENT
 * @method static COMMAND
 * @method static QUERY
 */
final class MessageType extends Enum
{
    public const EVENT = 'event';
    public const COMMAND = 'command';
    public const QUERY = 'query';
}
