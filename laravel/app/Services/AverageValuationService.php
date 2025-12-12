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
    /**
     * Record stock inflow (purchase/addition)
     */
    public function applyIn(string $productId, int $quantity, float $unitCost)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Create stock movement record
        StockMovement::create([
            'product_id' => $productId,
            'company_id' => $user->company_id,
            'type' => 'in',
            'quantity' => $quantity,
            'unit_price' => $unitCost,
            'reference' => 'Stock In',
        ]);

        // Update inventory quantity
        $inventory = \App\Models\Inventory::where('product_id', $productId)->first();
        if ($inventory) {
            $inventory->increment('quantity', $quantity);
        }
    }

    /**
     * Record stock outflow (sale/reduction)
     */
    public function applyOut(string $productId, int $quantity)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Create stock movement record
        StockMovement::create([
            'product_id' => $productId,
            'company_id' => $user->company_id,
            'type' => 'out',
            'quantity' => $quantity,
            'reference' => 'Stock Out',
        ]);

        // Update inventory quantity
        $inventory = \App\Models\Inventory::where('product_id', $productId)->first();
        if ($inventory) {
            $inventory->decrement('quantity', $quantity);
        }
    }}
