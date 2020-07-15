<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace Tests\Unit\Services;

use App\Contracts\Models\Product;
use App\Models\Products\BassGuitar;
use App\Models\Products\Drum;
use App\Models\Products\Guitar;
use App\Models\Warehouse;
use App\Services\WarehouseManager;
use Tests\TestCase;

/**
 * Class WarehouseManagerTest
 * @package Tests\Unit\Services
 */
class WarehouseManagerTest extends TestCase
{
    /**
     * @var WarehouseManager
     */
    private WarehouseManager $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new WarehouseManager;
    }

    /** @test */
    public function it_can_make_warehouses()
    {
        foreach (range(1, $numberOfWarehouses = 3) as $_) {
            $this->makeWarehouse();
        }

        foreach ($this->service->warehouses as $warehouse) {
            $this->assertInstanceOf(Warehouse::class, $warehouse);
        }

        $this->assertCount($numberOfWarehouses, $this->service->warehouses);
    }

    #region Adding products

    /**
     * @test
     * @dataProvider products
     *
     * @param string $productType
     */
    public function it_can_add_a_product_to_a_warehouse(string $productType)
    {
        $this->makeWarehouse();

        $this->service->addProduct($product = new $productType);

        $this->assertOnStock($product);
    }

    /**
     * @test
     * @dataProvider products
     *
     * @param string $productType
     */
    public function it_separates_quantity_to_add_between_available_warehouses(string $productType)
    {
        $this->makeWarehouse(5);
        $this->makeWarehouse(5);

        $this->service->addProduct(
            $product = new $productType,
            $quantity = 7
        );

        /** @var Warehouse $warehouse1 */
        $warehouse1 = $this->service->warehouses->first();

        $this->assertCount(1, $warehouse1->stock);

        $stock1 = $warehouse1->stock->first();

        // Asserting the product is on stock with 5 quantity.
        $this->assertEquals($product, $stock1->product);
        $this->assertEquals(5, $stock1->quantity);

        /** @var Warehouse $warehouse2 */
        $warehouse2 = $this->service->warehouses->last();

        $this->assertCount(1, $warehouse2->stock);

        $stock2 = $warehouse2->stock->first();

        // Asserting the same product is available with 2 pieces (the remaining quantity) on another stock.
        $this->assertEquals($product, $stock2->product);
        $this->assertEquals(2, $stock2->quantity);
    }

    /** @test */
    public function it_can_add_multiple_product_types()
    {
        $this->makeWarehouse(6);

        $this->service->addProduct($guitar = new Guitar, $numberOfGuitars = 3);
        $this->service->addProduct($drum = new Drum, $numberOfDrums = 2);
        $this->service->addProduct($bassGuitar = new BassGuitar, $numberOfBassGuitars = 1);

        $this->assertOnStockWithQuantity($guitar, $numberOfGuitars);
        $this->assertOnStockWithQuantity($drum, $numberOfDrums);
        $this->assertOnStockWithQuantity($bassGuitar, $numberOfBassGuitars);
    }

    /**
     * @test
     * @dataProvider products
     * @expectedException \App\Exceptions\NotEnoughStockCapacityException
     *
     * @param string $productType
     */
    public function it_throws_an_exception_when_the_stock_is_less_than_the_quantity_to_add(string $productType)
    {
        $this->makeWarehouse(1);

        $this->service->addProduct(new $productType, 2);
    }

    #endregion Adding products

    #region Removing products

    /**
     * @test
     * @dataProvider products
     *
     * @param string $productType
     */
    public function it_can_remove_a_product_from_a_warehouse(string $productType)
    {
        $this->makeWarehouse();

        $this->service->addProduct($product = new $productType);

        $this->assertOnStock($product);

        $this->service->removeProduct($product);

        $this->assertOutOfStock($product);
    }

    /**
     * @test
     * @dataProvider products
     *
     * @param string $productType
     */
    public function it_can_remove_a_product_from_multiple_warehouses(string $productType)
    {
        $this->makeWarehouse(5);
        $this->makeWarehouse(5);

        $this->service->addProduct(
            $product = new $productType,
            $quantity = 7
        );

       $this->assertOnStockWithQuantity($product, $quantity);

       $this->service->removeProduct($product, 7);

       $this->assertOutOfStock($product);
    }

    /**
     * @test
     * @dataProvider products
     *
     * @param string $productType
     */
    public function it_only_removes_the_available_stock_when_greater_quantity_is_given(string $productType)
    {
        $this->makeWarehouse();

        $this->service->addProduct($product = new $productType);

        $this->assertOnStockWithQuantity($product, 1);

        $this->service->removeProduct($product, 5);

        $this->assertOutOfStock($product);
    }

    /** @test */
    public function it_only_removes_the_corresponding_products()
    {
        $this->makeWarehouse(5);
        $this->makeWarehouse(5);

        $this->service->addProduct($guitar = new Guitar, $numberOfGuitars = 3);
        $this->service->addProduct($drum = new Drum, $numberOfDrums = 2);
        $this->service->addProduct($bassGuitar = new BassGuitar, $numberOfBassGuitars = 1);

        // Asserting only one guitar was removed
        $this->service->removeProduct($guitar, 1);
        $this->assertOnStockWithQuantity($guitar, --$numberOfGuitars);

        // Asserting all the guitars were removed
        $this->service->removeProduct($guitar, $numberOfGuitars);
        $this->assertOutOfStock($guitar);

        // Asserting other product types are still on stock
        $this->assertOnStockWithQuantity($drum, $numberOfDrums);
        $this->assertOnStockWithQuantity($bassGuitar, $numberOfBassGuitars);
    }

    #endregion Removing products

    public function products(): array
    {
        return [
            [Guitar::class],
            [Drum::class],
            [BassGuitar::class],
        ];
    }

    #region Helpers

    private function makeWarehouse(?int $capacity = null): Warehouse
    {
        return $this->service->make(
            $this->faker->name,
            $this->faker->address,
            $capacity ?? $this->faker->numberBetween(1, 10)
        );
    }

    private function assertOnStock(Product $product): void
    {
        $productIsAvailable = $this->service
            ->warehouses
            ->filter(function (Warehouse $warehouse) use ($product) {
                return $warehouse->stock->where('product', $product)->isNotEmpty();
            })
            ->isNotEmpty();

        $this->assertTrue($productIsAvailable);
    }

    private function assertOnStockWithQuantity(Product $product, int $expectedQuantity): void
    {
        $availableQuantity = $this->service
            ->warehouses
            ->sum(function (Warehouse $warehouse) use ($product) {
                return $warehouse->stock->where('product', $product)->sum('quantity');
            });

        $this->assertEquals($expectedQuantity, $availableQuantity);
    }

    private function assertOutOfStock(Product $product): void
    {
        $availableQuantity = $this->service
            ->warehouses
            ->sum(function (Warehouse $warehouse) use ($product) {
                return $warehouse->stock->where('product', $product)->sum('quantity');
            });

        $this->assertEquals(0, $availableQuantity);
    }

    #endregion Helpers
}
