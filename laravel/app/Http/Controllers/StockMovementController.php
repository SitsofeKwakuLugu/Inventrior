<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    /**
     * Display a listing of stock movements.
     */
    public function index()
    {
        $user = Auth::user();

        $movements = $user->hasRole('super-admin')
            ? StockMovement::with('product')->latest()->paginate(20)
            : StockMovement::with('product')
                ->where('company_id', $user->company_id)
                ->latest()
                ->paginate(20);

        return view('stockmovements.index', compact('movements'));
    }
}
