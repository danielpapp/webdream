<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Models\Products;

use App\Contracts\Models\Product as ProductContract;
use App\Models\Brand;

/**
 * Class Product
 * @package App\Models\Products
 */
class Product implements ProductContract
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $articleNumber;

    /**
     * @var float
     */
    public float $price;

    /**
     * @var Brand
     */
    public Brand $brand;

    /**
     * Product constructor.
     * @param string|null $name
     * @param string|null $articleNumber
     * @param float|null  $price
     * @param Brand|null  $brand
     */
    public function __construct(
        ?string $name = null,
        ?string $articleNumber = null,
        ?float $price = null,
        ?Brand $brand = null
    ) {
        if ($name) {
            $this->name = $name;
        }

        if ($articleNumber) {
            $this->articleNumber = $articleNumber;
        }

        if ($price) {
            $this->price = $price;
        }

        if ($brand) {
            $this->brand = $brand;
        }
    }

    #region Product contract implementation

    public function getArticleNumber(): ?string
    {
        return isset($this->articleNumber)
            ? $this->articleNumber
            : null;
    }

    public function getName(): ?string
    {
        return isset($this->name)
            ? $this->name
            : null;
    }

    public function getPrice(): ?float
    {
        return isset($this->price)
            ? $this->price
            : null;
    }

    public function getBrand(): ?Brand
    {
        return isset($this->brand)
            ? $this->brand
            : null;
    }

    #endregion Product contract implementation
}
