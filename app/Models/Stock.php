<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Models;

use App\Contracts\Models\Product;

/**
 * Class Stock
 * @package App\Models
 */
class Stock
{
    /**
     * @var int
     */
    public int $quantity;

    /**
     * @var Product
     */
    public Product $product;

    public function __construct(Product $product, int $quantity = 1)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }
}
