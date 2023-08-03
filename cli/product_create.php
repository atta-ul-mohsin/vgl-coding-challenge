<?php declare(strict_types=1);

require_once __DIR__ . '/../src/Product.php';
require_once __DIR__ . '/../src/Storage/Writer.php';
require_once __DIR__ . '/../src/Event/EventQueue.php';

use App\Product;
use App\Storage\Writer;
use App\Event\EventQueue;

// Get product data from command line arguments
if (count($argv) !== 4) {
    echo "Usage: php cli/product_create.php <id> <name> <price>\n";
    exit(1);
}

$id = (int) $argv[1]; // product ID as an integer
$name = $argv[2]; // product name as a string
$price = $argv[3]; // product price as a string

// Validate the price format with EURO format (e.g., 100.000,00)
if (!preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{2})?$/', $price)) {
    echo "Invalid price format. Please provide a valid price in EURO format (e.g., 100.000,00).\n";
    exit(1);
}

// Normalize the price by removing thousands separators (dots) and converting commas to dots
$price = (float) str_replace(['.', ','], ['', '.'], $price);

// Create a Product instance
$product = new Product($id, $name, $price);

// Serialize product data to store in storage
$data = json_encode($product);

// Write the product data to the storage
$writer = new Writer();
$writer->create('product_' . $id . '.json', $data);

// Queue event for product creation
$eventQueue = new EventQueue();
$eventQueue->enqueueEvent('product_created', ['id' => $id, 'name' => $name, 'price' => $price]);

/*// Generate the message for the CLI output
$message = "Product created: $id $name $price";
echo $message . PHP_EOL;*/
