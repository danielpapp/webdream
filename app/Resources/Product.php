<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Resources;

use App\Contracts\Models\Instrument;
use App\Contracts\Models\Product as ProductContract;
use Tightenco\Collect\Contracts\Support\Arrayable;

/**
 * Class Product
 * @package App\Resources
 */
class Product implements Arrayable
{
    /**
     * @var ProductContract
     */
    private ProductContract $product;

    /**
     * Product constructor.
     * @param ProductContract $product
     */
    public function __construct(ProductContract $product)
    {
        $this->product = $product;
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->product->getName(),
            'price' => $this->product->getPrice(),
            'article_number' => $this->product->getArticleNumber(),
            'brand' => ($brand = $this->product->getBrand()) ? $brand->name : 'unknown',
        ];

        if ($this->product instanceof Instrument) {
            $data['sound'] = $this->product->play();
        }

        return $data;
    }
}
