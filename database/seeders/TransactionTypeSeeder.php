<?php

namespace Err0r\Laratransaction\Database\Seeders;

use \Err0r\Laratransaction\Enums\TransactionType as TransactionTypeEnum;
use Err0r\Laratransaction\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        $records = collect(TransactionTypeEnum::cases())->map(fn ($status) => [
            'slug' => $status->value,
        ])->toArray();

        $locales = collect(config('laratransaction.localization.active_locales'));
        foreach ($records as $key => $record) {
            $records[$key]['name'] = $locales->mapWithKeys(fn ($locale) => [
                $locale => __('laratransaction::transaction.type.' . $record['slug'], locale: $locale)
            ])->toArray();
        }

        foreach ($records as $record) {
            TransactionType::updateOrCreate(
                ['slug' => $record['slug']],
                $record
            );
        }
    }
}
