<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AirtimeController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $userId = $request->user()->id;
        $amount = $request->amount;

        DB::transaction(function () use ($userId, $amount) {
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();
            if (!$wallet || $wallet->balance < $amount) {
                throw new \Exception('Insufficient balance');
            }

            // Deduct balance
            $wallet->balance -= $amount;
            $wallet->save();

            // Log transaction
            Transaction::create([
                'user_id' => $userId,
                'type' => 'purchase',
                'amount' => $amount,
                'description' => 'Airtime purchase',
            ]);
        });

        return response()->json(['status' => true, 'message' => 'Airtime purchased successfully']);
    }
}
