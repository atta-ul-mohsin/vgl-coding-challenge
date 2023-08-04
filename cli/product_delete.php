<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Storage\Writer;
use App\Storage\Reader;
use App\Event\EventQueue;

// Get the product ID to delete from command line arguments
if (count($argv) !== 2) {
    echo "Usage: php cli/product_delete.php <id>\n";
    exit(1);
}

$id = (int) $argv[1]; // product ID as an integer

// Create instances of Reader and Writer
$reader = new Reader();
$writer = new Writer();
// Delete the product from the storage
$writer->delete('product_' . $id . '.json');

// Queue event for product deletion
$eventQueue = new EventQueue($reader, $writer);
$eventQueue->enqueueEvent('product_deleted', ['id' => $id]);

