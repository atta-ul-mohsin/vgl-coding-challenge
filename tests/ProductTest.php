<?php declare(strict_types=1);

namespace Tests;

use App\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductConstructor(): void
    {
        $product = new Product(1, 'Test Product', 9.99);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame(1, $product->id);
        $this->assertSame('Test Product', $product->name);
        $this->assertSame(9.99, $product->price);
    }

    public function testGetChanges(): void
    {
        $originalProduct = new Product(1, 'Test Product', 9.99);
        $updatedProduct = new Product(1, 'Updated Product', 12.49);

        $changes = $originalProduct->getChanges($updatedProduct);

        $this->assertIsArray($changes);
        $this->assertCount(2, $changes);
        $this->assertContains('name', $changes);
        $this->assertContains('price', $changes);
    }

    public function testGetChangesNoChanges(): void
    {
        $product = new Product(1, 'Test Product', 9.99);

        $changes = $product->getChanges($product);

        $this->assertIsArray($changes);
        $this->assertEmpty($changes);
    }
}
