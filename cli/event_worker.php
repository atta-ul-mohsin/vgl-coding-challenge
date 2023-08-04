<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Event\EventProcessor;
use App\Storage\Reader;
use App\Storage\Writer;

// Create instances of Reader and Writer
$reader = new Reader();
$writer = new Writer();

// Create an instance of the EventProcessor and inject the dependencies
$eventProcessor = new EventProcessor($reader, $writer);

// Process events from the queue
$output = $eventProcessor->processEvents();

// Print the output to the CLI
echo $output;
