<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\AverageValuationService;

class InventoryController extends Controller
{
    protected AverageValuationService $valuation;

    public function __construct(AverageValuationService $valuation)
    {
        $this->valuation = $valuation;
    }

    /**
     * Display a list of inventories.
     */
    public function index()
    {
        $user = Auth::user();

        $inventories = $user->hasRole('super-admin')
            ? Inventory::with('product')->paginate(20)
            : Inventory::with('product')
                ->where('company_id', $user->company_id)
                ->paginate(20);

        return view('inventory.index', compact('inventories'));
    }

    /**
     * Add stock to a product in inventory or create inventory if it doesn't exist.
     */
    public function addStock(Request $request, $inventoryOrProduct)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        // Check if it's a Product ID or Inventory ID
        // Try to find as Product first
        $product = Product::find($inventoryOrProduct);
        
        if ($product) {
            // It's a product ID - create inventory if it doesn't exist
            if (!$product->inventory) {
                Inventory::create([
                    'product_id' => $product->id,
                    'company_id' => $user->company_id,
                    'quantity' => $data['quantity'],
                    'unit_price' => $data['unit_cost'],
                    'location' => null,
                ]);
                return back()->with('success', 'Inventory created successfully.');
            } else {
                // Product has inventory, add stock
                $this->valuation->applyIn(
                    $product->id,
                    $data['quantity'],
                    $data['unit_cost']
                );
                return back()->with('success', 'Stock added.');
            }
        } else {
            // It's an Inventory ID
            $inventory = Inventory::findOrFail($inventoryOrProduct);
            $this->valuation->applyIn(
                $inventory->product_id,
                $data['quantity'],
                $data['unit_cost']
            );
            return back()->with('success', 'Stock added.');
        }
    }

    /**
     * Reduce stock of a product in inventory.
     */
    public function reduceStock(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->valuation->applyOut(
            $inventory->product_id,
            $data['quantity']
        );

        return back()->with('success', 'Stock reduced.');
    }
}
