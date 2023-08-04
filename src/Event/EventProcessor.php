<?php declare(strict_types=1);

namespace App\Event;


use App\Storage\Reader;
use App\Storage\Writer;

class EventProcessor
{
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var Writer
     */
    private $writer;

    /**
     * @param Reader $reader
     * @param Writer $writer
     */
    public function __construct(Reader $reader, Writer $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * @return string
     */
    public function processEvents(): string
    {
        $output = '';

        while (true) {
            $event = $this->getNextEvent();

            if ($event === null) {
                break;
            }

            $message = $this->processEvent($event);

            if ($message !== null) {
                $output .= $message . "\n";
            }
        }

        return $output;
    }

    /**
     * @return array|null
     */
    private function getNextEvent(): ?array
    {
        $eventsData = $this->reader->read('events.json');
        $events = json_decode($eventsData, true);

        if (!is_array($events) || count($events) === 0) {
            return null; // No events found in the queue
        }

        $nextEvent = array_shift($events);

        if (!isset($nextEvent['type']) || !isset($nextEvent['data'])) {
            // Invalid event format, log an error and skip this event
            // Or you can handle the error in any way that suits your application
            error_log('Invalid event format: ' . print_r($nextEvent, true));
            return null;
        }

        // Save the updated events back to the file
        $updatedEventsData = json_encode(array_values($events));
        $this->writer->update('events.json', $updatedEventsData);

        return $nextEvent;
    }

    /**
     * @param array $event
     * @return string|null
     */
    private function processEvent(array $event): ?string
    {
        $eventType = $event['type'];
        $data = $event['data'];

        switch ($eventType) {
            case 'product_created':
                return "Product created: {$data['id']} {$data['name']} {$data['price']}";
            case 'product_updated':
                return "Product updated: ".implode(',', $data['changes'])."";
            case 'product_deleted':
                return "Product deleted: {$data['id']}";
            default:
                // Unknown event type, ignore or log the error.
                return null;
        }
    }
}
