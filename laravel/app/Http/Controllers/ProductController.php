<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $user = Auth::user();

        $products = $user->hasRole('super-admin')
            ? Product::latest()->paginate(20)
            : Product::where('company_id', $user->company_id)
                ->latest()
                ->paginate(20);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $path = 'uploads/' . $filename;
        }

        Product::create([
            'company_id' => $user->company_id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image_path' => $path,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    /**
     * Show the form for editing a product.
     */
    public function edit(Product $product)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && $product->company_id !== $user->company_id) {
            abort(403);
        }

        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $user = $request->user();
        if (!$user->hasRole('super-admin') && $product->company_id !== $user->company_id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path && file_exists(public_path($product->image_path))) {
                @unlink(public_path($product->image_path));
            }

            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image_path'] = 'uploads/' . $filename;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && $product->company_id !== $user->company_id) {
            abort(403);
        }

        if ($product->image_path && file_exists(public_path($product->image_path))) {
            @unlink(public_path($product->image_path));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && $product->company_id !== $user->company_id) {
            abort(403);
        }

        return view('products.show', compact('product'));
    }
}
