<?php declare(strict_types=1);

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
    /**
     * Event type
     *
     * @var string
     */
    public const EVENT = 'event';

    /**
     * Command type
     *
     * @var string
     */
    public const COMMAND = 'command';

    /**
     * Query type
     *
     * @var string
     */
    public const QUERY = 'query';
}
