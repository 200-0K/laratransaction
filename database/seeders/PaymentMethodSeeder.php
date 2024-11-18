<?php

namespace Err0r\Laratransaction\Database\Seeders;

use Err0r\Laratransaction\Enums\PaymentMethod as PaymentMethodEnum;
use Err0r\Laratransaction\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = collect(PaymentMethodEnum::cases())->map(fn ($status) => [
            'slug' => $status->value,
        ])->toArray();

        $locales = collect(config('laratransaction.localization.active_locales'));
        foreach ($records as $key => $record) {
            $records[$key]['name'] = $locales->mapWithKeys(fn ($locale) => [
                $locale => __('laratransaction::transaction.payment_method.'.$record['slug'], locale: $locale),
            ])->toArray();
        }

        foreach ($records as $record) {
            PaymentMethod::updateOrCreate(
                ['slug' => $record['slug']],
                $record
            );
        }
    }
}
