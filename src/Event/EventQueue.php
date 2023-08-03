<?php declare(strict_types=1);

namespace App\Event;

use App\Storage\Writer;
use App\Storage\Reader;

class EventQueue
{
    private $writer;
    private $reader;

    public function __construct()
    {
        $this->writer = new Writer();
        $this->reader = new Reader();
    }

    public function enqueueEvent(string $eventType, array $data): void
    {
        $event = [
            'type' => $eventType,
            'data' => $data,
        ];

        $this->saveEvent($event);
    }

    private function saveEvent(array $event): void
    {
        $eventsData = $this->reader->read('events.json');
        $events = json_decode($eventsData, true);

        if (!is_array($events)) {
            $events = [];
        }

        $events[] = $event;

        $updatedEventsData = json_encode(['events' => $events]);
        $this->writer->update('events.json', $updatedEventsData);
    }
}
