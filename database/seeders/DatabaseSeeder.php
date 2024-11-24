<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $currentTime = now(); // Get the current timestamp

        // Insert users
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
        ]);

        // Insert wallets
        DB::table('wallets')->insert([
            [
                'user_id' => 1,
                'balance' => 500.00,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'user_id' => 2,
                'balance' => 300.00,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
        ]);

        // Insert transactions
        DB::table('transactions')->insert([
            // Transactions for John Doe
            [
                'user_id' => 1,
                'type' => 'funding',
                'amount' => 500.00,
                'description' => 'Initial wallet funding',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'user_id' => 1,
                'type' => 'purchase',
                'amount' => 0.00,
                'description' => 'No purchases yet',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],

            // Transactions for Jane Smith
            [
                'user_id' => 2,
                'type' => 'funding',
                'amount' => 400.00,
                'description' => 'Initial wallet funding',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'user_id' => 2,
                'type' => 'purchase',
                'amount' => 100.00,
                'description' => 'Airtime purchase',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
        ]);
    }
}
