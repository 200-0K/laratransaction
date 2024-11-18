<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    |
    | Database table configuration. You can customize table names and
    | UUID settings for each entity. Default names are provided but
    | can be overridden using environment variables.
    |
    */

    'tables' => [
        'transactionable' => [
            'uuid' => env('LARATRANSACTION_TABLE_TRANSACTIONABLE_UUID', false),
        ],
        'transaction_statuses' => [
            'name' => env('LARATRANSACTION_TABLE_TRANSACTION_STATUSES', 'transaction_statuses'),
            'uuid' => env('LARATRANSACTION_TABLE_TRANSACTION_STATUSES_UUID', true),
        ],
        'transaction_types' => [
            'name' => env('LARATRANSACTION_TABLE_TRANSACTION_TYPES', 'transaction_types'),
            'uuid' => env('LARATRANSACTION_TABLE_TRANSACTION_TYPES_UUID', true),
        ],
        'payment_methods' => [
            'name' => env('LARATRANSACTION_TABLE_PAYMENT_METHODS', 'payment_methods'),
            'uuid' => env('LARATRANSACTION_TABLE_PAYMENT_METHODS_UUID', true),
        ],
        'transactions' => [
            'name' => env('LARATRANSACTION_TABLE_TRANSACTIONS', 'transactions'),
            'uuid' => env('LARATRANSACTION_TABLE_TRANSACTIONS_UUID', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Model class mappings. These classes handle the business logic
    | for transaction_statuses, transactions, subscriptions, and related
    | entities. You can extend or replace these with your own implementations.
    |
    */

    'models' => [
        'transaction' => \Err0r\Laratransaction\Models\Transaction::class,
        'transaction_status' => \Err0r\Laratransaction\Models\TransactionStatus::class,
        'transaction_type' => \Err0r\Laratransaction\Models\TransactionType::class,
        'payment_method' => \Err0r\Laratransaction\Models\PaymentMethod::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Localization configuration. You can customize the active locales
    | for the system. Default locales are provided but can be overridden
    | using environment variables.
    |
    */

    'localization' => [
        'active_locales' => explode(',', env('LARATRANSACTION_ACTIVE_LOCALES', 'ar,en')),
    ]
];
