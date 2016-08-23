<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model\Api;

use Novuso\Common\Domain\Messaging\Event\Event;

/**
 * AggregateRoot is the interface for a root entity
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface AggregateRoot
{
    /**
     * Removes and returns recorded events
     *
     * @return Event[]
     */
    public function extractRecordedEvents(): array;
}
