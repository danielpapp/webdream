<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Contracts\Models;

use App\Models\Brand;

/**
 * Interface Product
 * @package App\Contracts\Models
 */
interface Product
{
    public function getArticleNumber(): ?string;

    public function getName(): ?string;

    public function getPrice(): ?float;

    public function getBrand(): ?Brand;
}
