<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // Get all transactions for the authenticated user
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $transactions = Transaction::where('user_id', $userId)->get();

        return response()->json(['status' => true, 'transactions' => $transactions]);
    }

    // Create a new transaction
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:funding,purchase',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $userId = $request->user()->id;

        $transaction = Transaction::create([
            'user_id' => $userId,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return response()->json(['status' => true, 'transaction' => $transaction]);
    }
}
