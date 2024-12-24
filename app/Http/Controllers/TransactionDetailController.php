<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'transaction_id' => 'required|uuid|exists:transactions,id',
                'product_id' => 'required|uuid|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|integer',
            ]);

            $validated['subtotal'] = $validated['price'] * $validated['quantity'];

            $transactionDetail = TransactionDetail::create($validated);

            $product = Product::findOrFail($validated['product_id']);
            $product->stock -= $validated['quantity'];
            $product->save();

            return response()->json($transactionDetail, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating transaction detail', 'message' => $e->getMessage()], 500);
        }
    }
}
