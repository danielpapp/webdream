<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Services;

use App\Contracts\Models\Product;
use App\Exceptions\NotEnoughStockCapacityException;
use App\Models\Stock;
use App\Models\Warehouse;
use Tightenco\Collect\Support\Collection;

/**
 * Class WarehouseManager
 * @package App\Services
 */
class WarehouseManager
{
    /**
     * @var Collection
     */
    public Collection $warehouses;

    /**
     * WarehouseManager constructor.
     * @param Collection|null $warehouses
     */
    public function __construct(?Collection $warehouses = null)
    {
        $this->warehouses = $warehouses ?? collect();
    }

    public function make(string $name, string $address, int $capacity = 1): Warehouse
    {
        $warehouse = new Warehouse($name, $address, $capacity);

        $this->warehouses->push($warehouse);

        return $warehouse;
    }

    public function addProduct(Product $product, int $quantity = 1): self
    {
        if ($quantity > $this->availableStockCapacity()) {
            throw new NotEnoughStockCapacityException;
        }

        foreach ($this->warehouses as $warehouse) {
            $quantityToAdd = min($quantity, $warehouse->getAvailableStockCapacity());

            $this->addProductToWarehouse($warehouse, $product, $quantityToAdd);

            $quantity -= $quantityToAdd;

            if ($quantity <= 0) {
                break;
            }
        }

        return $this;
    }

    public function removeProduct(Product $product, int $quantity = 1): self
    {
        $this->warehouses
            ->map(function (Warehouse $warehouse) use ($product) {
                return $warehouse->stock->where('product', $product);
            })
            ->reject(function (Collection $stock) {
                return $stock->isEmpty();
            })
            ->flatten()
            ->each(function (Stock $stock) use (&$quantity) {
                $quantityToRemove = min($quantity, $stock->quantity);

                $stock->quantity -= $quantityToRemove;

                $quantity -= $quantityToRemove;

                // Stop iteration when quantity to remove is 0.
                return $quantity <= 0
                    ? false
                    : true;
            });

        return $this;
    }

    private function availableStockCapacity(): int
    {
        return $this->warehouses->sum->getAvailableStockCapacity();
    }

    private function addProductToWarehouse(Warehouse $warehouse, Product $product, int $quantity = 1): void
    {
        $warehouse->stock->push(new Stock($product, $quantity));
    }
}
