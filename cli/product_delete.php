<?php declare(strict_types=1);

require_once __DIR__ . '/../src/Storage/Writer.php';
require_once __DIR__ . '/../src/Event/EventQueue.php';

use App\Storage\Writer;
use App\Event\EventQueue;

// Get the product ID to delete from command line arguments
if (count($argv) !== 2) {
    echo "Usage: php cli/product_delete.php <id>\n";
    exit(1);
}

$id = (int) $argv[1]; // product ID as an integer

// Delete the product from the storage
$writer = new Writer();
$writer->delete('product_' . $id . '.json');

// Queue event for product deletion
$eventQueue = new EventQueue();
$eventQueue->enqueueEvent('product_deleted', ['id' => $id]);

