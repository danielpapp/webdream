<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace Tests;

use Faker\Factory;
use Faker\Generator;

/**
 * Class TestCase
 * @package Tests
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Generator
     */
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }
}
