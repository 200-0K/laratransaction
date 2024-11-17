<?php

namespace Err0r\Laratransaction\Commands;

use Err0r\Laratransaction\Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;

class LaratransactionSeed extends Command
{
    public $signature = 'laratransaction:seed';

    public $description = 'Seed the database with the necessary data';

    public function handle(): int
    {
        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);

        return self::SUCCESS;
    }
}
