<?php declare(strict_types=1);

namespace Tests;

use App\Event\EventProcessor;
use App\Storage\Reader;
use App\Storage\Writer;
use PHPUnit\Framework\TestCase;

class EventProcessorTest extends TestCase
{
    private $readerMock;
    private $writerMock;
    private $eventProcessor;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock Reader and Writer for each test
        $this->readerMock = $this->createMock(Reader::class);
        $this->writerMock = $this->createMock(Writer::class);

        // Create the EventProcessor instance and inject the mock reader and writer
        $this->eventProcessor = new EventProcessor($this->readerMock, $this->writerMock);
    }

    public function testProcessEventsWithValidData()
    {
        // Set up the mock reader to return multiple calls
        $readerMock = $this->createMock(Reader::class);
        $readerMock
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                '[{"type": "product_created", "data": {"id": 1, "name": "Product A", "price": 100}}]',
                '[{"type": "product_updated", "data": {"changes": ["price"]}}]',
                '[{"type": "product_deleted", "data": {"id": 2}}]',
                '[]' // For the case when no events are left
            );

        $writerMock = $this->createMock(Writer::class);
        $writerMock->expects($this->exactly(3))->method('update');

        // Create the EventProcessor instance and inject the mock reader and writer
        $eventProcessor = new EventProcessor($readerMock, $writerMock);

        // Execute the processEvents() method
        $output = $eventProcessor->processEvents();

        // Assert the expected output
        $this->assertEquals(
            "Product created: 1 Product A 100\nProduct updated: price\nProduct deleted: 2\n",
            $output
        );
    }

    public function testProcessEventsWithEmptyData()
    {
        // Set up the mock reader to return an empty array
        $this->readerMock->method('read')->willReturn('[]');

        // Execute the processEvents() method
        $output = $this->eventProcessor->processEvents();

        // Assert the expected output (empty string)
        $this->assertEquals('', $output);
    }

    public function testProcessEventsWithInvalidEventData()
    {
        // Set up the mock reader to return invalid JSON data
        $this->readerMock->method('read')->willReturn('{"invalid_key": "invalid_value"}');

        // Execute the processEvents() method
        $output = $this->eventProcessor->processEvents();

        // Assert the expected output (empty string, as the invalid event will be skipped)
        $this->assertEquals('', $output);
    }
}

