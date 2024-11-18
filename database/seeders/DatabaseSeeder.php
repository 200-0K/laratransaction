<?php

namespace Err0r\Laratransaction\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            TransactionStatusSeeder::class,
            TransactionTypeSeeder::class,
            PaymentMethodSeeder::class,
        ]);
    }
}
