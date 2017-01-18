<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use Novuso\System\Type\Enum;

/**
 * MessageType represents a type of message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class MessageType extends Enum
{
    /**
     * Event type
     *
     * @var string
     */
    const EVENT = 'event';

    /**
     * Command type
     *
     * @var string
     */
    const COMMAND = 'command';

    /**
     * Query type
     *
     * @var string
     */
    const QUERY = 'query';
}
