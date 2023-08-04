<?php declare(strict_types=1);

namespace App\Event;

use App\Storage\Writer;
use App\Storage\Reader;

class EventQueue
{
    /**
     * @var Writer
     */
    private $writer;
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     * @param Writer $writer
     */
    public function __construct(Reader $reader, Writer $writer)
    {
        $this->writer = $writer;
        $this->reader = $reader;
    }

    /**
     * @param string $eventType
     * @param array $data
     * @return void
     */
    public function enqueueEvent(string $eventType, array $data): void
    {
        $event = [
            'type' => $eventType,
            'data' => $data,
        ];

        $this->saveEvent($event);
    }

    /**
     * @param array $event
     * @return void
     */
    private function saveEvent(array $event): void
    {
        $eventsData = $this->reader->read('events.json');
        $events = json_decode($eventsData, true);

        if (!is_array($events)) {
            $events = [];
        }

        $events[] = $event;

        $updatedEventsData = json_encode($events);
        $this->writer->update('events.json', $updatedEventsData);
    }
}
