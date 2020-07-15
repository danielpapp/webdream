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
use Tests\TestCase;

/**
 * Class InstrumentTest
 * @package Tests\Unit\Model
 */
class InstrumentTest extends TestCase
{
    /**
     * @test
     * @dataProvider instruments
     *
     * @param string $type
     * @param string $sound
     */
    public function an_instrument_can_be_played(string $type, string $sound)
    {
        $this->assertEquals((new $type)->play(), $sound);
    }

    public function instruments(): array
    {
        return [
            [Guitar::class, 'Cool riffs on 6 strings!'],
            [Drum::class, 'Blast beats and cymbals!'],
            [BassGuitar::class, 'Cool riffs on 4 strings!'],
        ];
    }
}
