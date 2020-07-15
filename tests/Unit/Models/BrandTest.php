<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace Tests\Unit\Model;

use App\Models\Brand;
use Tests\TestCase;

/**
 * Class BrandTest
 * @package Tests\Unit\Model
 */
class BrandTest extends TestCase
{
    /**
     * @var Brand
     */
    private Brand $brand;

    public function setUp(): void
    {
        parent::setUp();

        $this->brand = new Brand($this->faker->name);
    }

    /**
     * @test
     * @dataProvider invalidQualityCategories
     * @expectedException \InvalidArgumentException
     *
     * @param int $value
     */
    public function it_throws_an_error_when_invalid_quality_category_is_given(int $value)
    {
        $this->expectExceptionMessage(
            $value < 1
                ? "Quality category must be greater than 0! [Current value: $value]"
                : "Quality category must be less than 5! [Current value: $value]"
        );

        $this->brand->setQualityCategory($value);
    }

    public function invalidQualityCategories(): array
    {
        return [
            [-1],
            [0],
            [6],
        ];
    }
}
