<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category' => $request->category,
            ]);

            return response()->json($product, 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage()); // Log error
            return response()->json(['error' => 'Failed to create product'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|integer',
            'stock' => 'nullable|integer',
            'category' => 'nullable|string|max:255',
        ]);
        Log::info($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $product->update($request->all());
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update product'], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        try {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete product'], 500);
        }
    }

    public function search(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $products = Product::where('name', 'ILIKE', '%' . $request->name . '%')
            ->orWhere('name', 'like', '%' . strtolower($request->name) . '%')
            ->paginate(10);

        return response()->json($products);
    }


    public function filterByCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);

        $products = Product::where('category', $request->category)->paginate(10);
        return response()->json($products);
    }
}
