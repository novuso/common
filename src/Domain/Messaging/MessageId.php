<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging;

use Novuso\Common\Domain\Identification\UniqueId;

/**
 * MessageId is a unique identifier for a message
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class MessageId extends UniqueId
{
    /**
     * {@inheritdoc}
     */
    public static function generate(): MessageId
    {
        /** @var MessageId $messageId */
        $messageId = parent::generate();

        return $messageId;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $value): MessageId
    {
        /** @var MessageId $messageId */
        $messageId = parent::fromString($value);

        return $messageId;
    }
}
