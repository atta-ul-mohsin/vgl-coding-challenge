<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Product;
use App\Storage\Writer;
use App\Storage\Reader;
use App\Event\EventQueue;

// Get updated product data from command line arguments
if (count($argv) !== 4) {
    echo "Usage: php cli/product_update.php <id> <name> <price>\n";
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

// Create a Product instance with the updated data
$updatedProduct = new Product($id, $name, $price);

// Read the existing product data from storage
$reader = new Reader();
$productData = $reader->read('product_' . $id . '.json');

// Deserialize the existing product data as an associative array
$existingProductData = json_decode($productData, true);

// Create a Product instance from the existing product data
$existingProduct = new Product(
    (int) $existingProductData['id'],
    $existingProductData['name'],
    (float) $existingProductData['price']
);

// Get the changes between existing and updated product
$changes = $existingProduct->getChanges($updatedProduct);

// Update the product in the storage
$writer = new Writer();
$writer->update('product_' . $id . '.json', json_encode($updatedProduct));

// Queue event for product update
$eventQueue = new EventQueue();
$eventQueue->enqueueEvent('product_updated', ['id' => $id, 'changes' => $changes]);
