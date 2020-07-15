<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Contracts\Models;

/**
 * Interface Instrument
 * @package App\Contracts\Models
 */
interface Instrument
{
    public function play(): string;
}
