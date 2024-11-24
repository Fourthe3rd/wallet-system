<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    // Show wallet details
    public function show(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not authenticated'], 401);
        }

        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json(['status' => false, 'message' => 'Wallet not found'], 404);
        }

        return response()->json(['status' => true, 'wallet' => $wallet]);
    }


    // Fund wallet
    public function fund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $userId = $request->user()->id;
        $amount = $request->amount;

        DB::transaction(function () use ($userId, $amount) {
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();
            if (!$wallet) {
                throw new \Exception('Wallet not found');
            }

            $wallet->balance += $amount;
            $wallet->save();

            Transaction::create([
                'user_id' => $userId,
                'type' => 'funding',
                'amount' => $amount,
                'description' => 'Wallet funding',
            ]);
        });

        return response()->json(['status' => true, 'message' => 'Wallet funded successfully']);
    }

    // Withdraw from wallet
    public function withdraw(Request $request)
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

            $wallet->balance -= $amount;
            $wallet->save();

            Transaction::create([
                'user_id' => $userId,
                'type' => 'withdrawal',
                'amount' => $amount,
                'description' => 'Wallet withdrawal',
            ]);
        });

        return response()->json(['status' => true, 'message' => 'Withdrawal successful']);
    }
}
