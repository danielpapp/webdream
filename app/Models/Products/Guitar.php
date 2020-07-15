<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Models\Products;

use App\Contracts\Models\Instrument;

/**
 * Class Guitar
 * @package App\Models\Products
 */
class Guitar extends Product implements Instrument
{
    protected const NUMBER_OF_STRINGS = 6;

    public function play(): string
    {
        return 'Cool riffs on ' . static::NUMBER_OF_STRINGS . ' strings!';
    }
}
