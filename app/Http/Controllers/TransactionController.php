<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::paginate(10);
        return response()->json($transactions);
    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json($transaction);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'transaction_date' => 'required|date',
                'total_amount' => 'required|integer',
                'created_by' => 'required|uuid',
            ]);

            $transaction = Transaction::create($validated);

            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating transaction', 'message' => $e->getMessage()], 500);
        }
    }
}
