<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Resources;

use App\Models\Stock;
use App\Models\Warehouse;
use Tightenco\Collect\Contracts\Support\Arrayable;
use Tightenco\Collect\Support\Collection;

/**
 * Class Warehouses
 * @package App\Resources
 */
class Warehouses implements Arrayable
{
    /**
     * @var Collection
     */
    private Collection $warehouses;

    /**
     * Warehouses constructor.
     * @param Collection $warehouses
     */
    public function __construct(Collection $warehouses)
    {
        $this->warehouses = $warehouses;
    }

    public function toArray(): array
    {
        return $this->warehouses->map(function (Warehouse $warehouse) {
            return [
                'name' => $warehouse->name . " ({$warehouse->address})",
                'capacity' => $warehouse->capacity,
                'available_stock_capacity' => $warehouse->getAvailableStockCapacity(),
                'items' => $warehouse->stock->map(function (Stock $stock) {
                    return [
                        'product' => (new Product($stock->product))->toArray(),
                        'quantity' => $stock->quantity,
                    ];
                })
            ];
        })->toArray();
    }
}
