<?php declare(strict_types=1);

namespace Tests;

use App\Event\EventQueue;
use App\Storage\Reader;
use App\Storage\Writer;
use PHPUnit\Framework\TestCase;

class EventQueueTest extends TestCase
{
    public function testEnqueueEvent()
    {
        // Mock the Writer and Reader
        $readerMock = $this->createMock(Reader::class);
        $writerMock = $this->createMock(Writer::class);

        // Set up the mock Reader to return empty array for the first call and single event for the second call
        $readerMock
            ->method('read')
            ->willReturnOnConsecutiveCalls('[]', '[{"type": "product_created", "data": {"id": 1, "name": "Product A", "price": 100}}]');

        // Expect the Writer's 'update' method to be called once
        $writerMock->expects($this->once())->method('update');

        // Create the EventQueue instance and inject the mock Reader and Writer
        $eventQueue = new EventQueue($readerMock, $writerMock);

        // Enqueue an event
        $eventQueue->enqueueEvent('product_created', ['id' => 1, 'name' => 'Product A', 'price' => 100]);

        // No assertion needed, the test will fail if the Writer's 'update' method is not called as expected
    }
}
