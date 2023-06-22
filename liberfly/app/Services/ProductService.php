<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductService
{
    public function create(array $data): Product
    {
        $product = new Product($data);
        $product->save();

        return $product;
    }

    public function getAll(): Collection
    {
        return Product::all();
    }

    public function getById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function update($data, $id)
    {
        $product = $this->getById($id);
        $product->update($data->all());
        $product->save();

        return $product;
    }

    public function delete(int $id): void
    {
        $product = $this->getById($id);
        $product->delete();
    }
}
