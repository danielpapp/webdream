<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Services\OutputFormatters;

use App\Contracts\Services\OutputFormatter;
use App\Resources\Warehouses;

/**
 * Class HtmlFormatter
 * @package App\Services\OutputFormatters
 */
class HtmlFormatter implements OutputFormatter
{
    protected const NEW_LINE = '<br/>';

    public function format(Warehouses $resource): string
    {
        $output = '';

        foreach ($resource->toArray() as $warehouse) {
            $output .= $this->formatWarehouseOutput($warehouse);
        }

        return $output;
    }

    private function formatWarehouseOutput(array $warehouse): string
    {
        $output = "Name: {$warehouse['name']}" . static::NEW_LINE
            . "Capacity: {$warehouse['capacity']}" . static::NEW_LINE
            . "Available stock capacity: {$warehouse['available_stock_capacity']}" . static::NEW_LINE
            . 'Current stock: ' . static::NEW_LINE . static::NEW_LINE;

        $i = 0;
        foreach ($warehouse['items'] as $item) {
            $output .= ++$i . ". {$item['product']['name']} ({$item['product']['article_number']})" . static::NEW_LINE
                . "Brand: {$item['product']['brand']}" . static::NEW_LINE
                . "Price: {$item['product']['price']}" . static::NEW_LINE;

            if (isset($item['product']['sound'])) {
                $output .= "Sound: {$item['product']['sound']}" . static::NEW_LINE;
            }

            $output .= "Quantity: {$item['quantity']}" . static::NEW_LINE . static::NEW_LINE;
        }

        return $output . static::NEW_LINE;
    }
}
