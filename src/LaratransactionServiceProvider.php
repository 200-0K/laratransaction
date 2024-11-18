<?php

namespace Err0r\Laratransaction;

use Err0r\Laratransaction\Commands\LaratransactionSeed;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaratransactionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laratransaction')
            ->hasConfigFile()
            ->hasMigrations([
                'create_transaction_statuses_table',
                'create_transaction_types_table',
                'create_payment_methods_table',
                'create_transactions_table',
            ])
            ->hasTranslations()
            ->hasCommand(LaratransactionSeed::class);
    }
}
