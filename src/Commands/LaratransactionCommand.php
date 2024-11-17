<?php

namespace Err0r\Laratransaction\Commands;

use Illuminate\Console\Command;

class LaratransactionCommand extends Command
{
    public $signature = 'laratransaction';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
