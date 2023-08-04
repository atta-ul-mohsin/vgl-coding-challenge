<?php declare(strict_types=1);

namespace App;

class Product
{
    /**
     * @var int
     */
    public int $id;
    /**
     * @var string
     */
    public string $name;
    /**
     * @var float
     */
    public float $price;

    /**
     * @param int $id
     * @param string $name
     * @param float $price
     */
    public function __construct(int $id, string $name, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @param Product $updatedProduct
     * @return array
     */
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
