<?php declare(strict_types=1);

namespace App;

class Product
{
    public int $id;
    public string $name;
    public float $price;

    public function __construct(int $id, string $name, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getChanges(Product $updatedProduct): array
    {
        $changes = [];
        if ($this->name !== $updatedProduct->name) {
            $changes[] = 'name';
        }
        if ($this->price !== $updatedProduct->price) {
            $changes[] = 'price';
        }
        return $changes;
    }
}
