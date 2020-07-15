<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Models;

use Tightenco\Collect\Support\Collection;

/**
 * Class Warehouse
 * @package App\Models
 */
class Warehouse
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $address;

    /**
     * @var int
     */
    public int $capacity;

    /**
     * @var Collection
     */
    public Collection $stock;

    public function __construct(string $name, string $address, int $capacity = 1, ?Collection $stock = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->capacity = $capacity;
        $this->stock = $stock ?? collect();
    }

    public function getAvailableStockCapacity(): int
    {
        return max(0, $this->capacity - $this->stock->sum('quantity'));
    }
}
