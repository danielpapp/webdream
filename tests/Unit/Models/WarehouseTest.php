<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace Tests\Unit\Model;

use App\Models\Products\BassGuitar;
use App\Models\Products\Drum;
use App\Models\Products\Guitar;
use App\Models\Stock;
use App\Models\Warehouse;
use Tests\TestCase;

/**
 * Class WarehouseTest
 * @package Tests\Unit\Model
 */
class WarehouseTest extends TestCase
{
    /**
     * @var Warehouse
     */
    private Warehouse $warehouse;

    public function setUp(): void
    {
        parent::setUp();

        $this->warehouse = new Warehouse($this->faker->name, $this->faker->address);
    }

    /** @test */
    public function it_can_calculate_available_stock_capacity()
    {
        $this->warehouse->capacity = 10;

        $this->warehouse->stock = collect([
            new Stock(new Guitar, 5),
            new Stock(new BassGuitar, 2),
            new Stock(new Drum, 1),
        ]);

        $this->assertEquals(2, $this->warehouse->getAvailableStockCapacity());
    }
}
