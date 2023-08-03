<?php declare(strict_types=1);

require_once __DIR__ . '/../src/Event/EventProcessor.php';

use App\Event\EventProcessor;

// Create an instance of the EventProcessor
$eventProcessor = new EventProcessor();

// Process events from the queue
$output = $eventProcessor->processEvents();

// Print the output to the CLI
echo $output;
