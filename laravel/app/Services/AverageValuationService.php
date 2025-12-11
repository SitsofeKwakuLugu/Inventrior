<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;

class AverageValuationService
{
    public function calculate(Product $product)
    {
        $movements = StockMovement::where('product_id', $product->id)->get();

        $totalCost = 0;
        $totalQuantity = 0;

        foreach ($movements as $mv) {
            if ($mv->type === 'in') {
                $totalCost += ($mv->quantity * $product->unit_price);
                $totalQuantity += $mv->quantity;
            }
        }

        return $totalQuantity > 0 ? $totalCost / $totalQuantity : 0;
    }
}
