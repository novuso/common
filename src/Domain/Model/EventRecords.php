<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Messaging\Event\Event;

/**
 * EventRecords provides methods for managing domain events
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
trait EventRecords
{
    /**
     * Event records
     *
     * @var Event[]
     */
    protected $eventRecords = [];

    /**
     * Removes and returns recorded events
     *
     * @return Event[]
     */
    public function extractRecordedEvents(): array
    {
        $records = $this->eventRecords;
        $this->eventRecords = [];

        return $records;
    }

    /**
     * Records a domain event
     *
     * @param Event $event The domain event
     *
     * @return void
     */
    protected function recordEvent(Event $event)
    {
        $this->eventRecords[] = $event;
    }
}
