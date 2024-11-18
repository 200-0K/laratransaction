<?php

namespace Err0r\Laratransaction\Database\Seeders;

use Err0r\Laratransaction\Enums\TransactionStatus as TransactionStatusEnum;
use Err0r\Laratransaction\Models\TransactionStatus;
use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = collect(TransactionStatusEnum::cases())->map(fn ($status) => [
            'slug' => $status->value,
        ])->toArray();

        $locales = collect(config('laratransaction.localization.active_locales'));
        foreach ($records as $key => $record) {
            $records[$key]['name'] = $locales->mapWithKeys(fn ($locale) => [
                $locale => __('laratransaction::transaction.status.'.$record['slug'], locale: $locale),
            ])->toArray();
        }

        foreach ($records as $record) {
            TransactionStatus::updateOrCreate(
                ['slug' => $record['slug']],
                $record
            );
        }
    }
}
