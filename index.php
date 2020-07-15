<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

use App\Contracts\Services\OutputFormatter;
use App\Models\Brand;
use App\Models\Products\BassGuitar;
use App\Models\Products\Drum;
use App\Models\Products\Guitar;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Resources\Warehouses;
use App\Services\OutputFormatters\CliFormatter;
use App\Services\OutputFormatters\HtmlFormatter;

// Register the auto loader
require __DIR__.'/vendor/autoload.php';

// Determine current invocation
$isRunningFromCli = php_sapi_name() === 'cli';

// Set correct break line character
$br = ($isRunningFromCli ? "\n" : '<br/>');

echo "List warehouses example$br$br";

// Building dummy data for demo
$gibson = new Brand('Gibson', 5);
$fender = new Brand('Fender', 4);
$zildijan = new Brand('Zildijan', 5);

$data = collect([
    new Warehouse('Warehouse 1', 'Budapest, Nagymező u. 3, 1065', 100, collect([
        new Stock(new Guitar('Les Paul', 'LP001', 1000, $gibson), 20),
        new Stock(new Guitar('SG', 'SG001', 5000, $fender), 20),
        new Stock(new BassGuitar('Thunderbird', 'TB001', 3000, $gibson), 10),
        new Stock(new Drum('Planet Z', 'PL001', 10000, $zildijan), 5),
    ])),
    new Warehouse('Warehouse 2', 'Budapest, Nagymező u. 5, 1065', 50, collect([
        new Stock(new BassGuitar('Vintera', 'VI001', 2000, $fender), 10),
        new Stock(new BassGuitar('Flea signature', 'FL001', 2500, $fender), 5),
        new Stock(new Drum('Splash', 'SP001', 5000, $zildijan), 20),
    ])),
]);

$formatterClass = ($isRunningFromCli ? CliFormatter::class : HtmlFormatter::class);

/** @var OutputFormatter $formatter */
$formatter = new $formatterClass;

echo $formatter->format(new Warehouses($data));
