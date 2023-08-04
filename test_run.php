<?php declare(strict_types=1);

// Id: 42, Name: Staubsauger, Price: 100,00
$output1 = shell_exec('php cli/product_create.php 42 Staubsauger 100,00');
$output2 = shell_exec('php cli/product_update.php 42 Staubsauger 150.000,00');
$output3 = shell_exec('php cli/product_delete.php 42');

$output = shell_exec('php cli/event_worker.php');

$expectedOutput = "Product created: 42 Staubsauger 100\nProduct updated: price\nProduct deleted: 42\n";

if ($output === $expectedOutput) {
    echo 'It works!' . PHP_EOL;
} else {
    echo "Something went wrong!\n";
    echo "Expected Output: $expectedOutput\n";
    echo "Actual Output: $output\n";
}