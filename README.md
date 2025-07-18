# Laravel Payment Transactions Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/err0r/laratransaction.svg?style=flat-square)](https://packagist.org/packages/err0r/laratransaction)
[![Total Downloads](https://img.shields.io/packagist/dt/err0r/laratransaction.svg?style=flat-square)](https://packagist.org/packages/err0r/laratransaction)

> [!IMPORTANT]  
> This package is currently under development and is **not yet ready for production use**.  
> Click the **Watch** button to stay updated and be notified when the package is ready for deployment!

This package provides a complete transaction management system for Laravel applications, offering powerful features: 

💳 **Payment Processing**
- Track payment transactions with multiple statuses (pending, completed, failed, cancelled)
- Support various transaction types (payment, refund)
- Handle diverse payment methods (credit card, bank transfer, cash, etc.)

🔗 **Flexible Integration**  
- Associate transactions with any model using polymorphic relationships
- Store rich transaction metadata
- Track payment gateway information and IDs

🌍 **Internationalization**
- Fully localized and translatable
- JSON-based translations

🛠️ **Developer Friendly**
- Fluent builder pattern for creating transactions
- Eloquent relationships and scopes
- API Resources for JSON responses
- Extensive configuration options

🔒 **Reliable & Secure**
- UUID support for better security
- Soft deletes for data integrity 
- Comprehensive audit trail
- Transaction history tracking

## Installation

You can install the package via composer:

```bash
composer require err0r/laratransaction
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="laratransaction-config"
```

Publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laratransaction-migrations"
php artisan migrate
```

Optionally, you can publish the translations using:

```bash
php artisan vendor:publish --tag="laratransaction-translations"
```

After that, you need to seed the transaction statuses, types, and payment methods:

```bash
php artisan laratransaction:seed
```

## Usage

### Adding Transactions to Your Models

Add the `HasTransaction` trait to any model that needs transaction support:

```php
use Err0r\Laratransaction\Traits\HasTransaction;

class Order extends Model
{
    use HasTransaction;
}
```

### Creating Transactions

Use the fluent helper method to create transactions:

```php
use Err0r\Laratransaction\Builders\TransactionBuilder;
use Err0r\Laratransaction\Enums\TransactionStatus;
use Err0r\Laratransaction\Enums\TransactionType;
use Err0r\Laratransaction\Enums\PaymentMethod;

// Create a transaction for an order
$order = Order::find(1);

$order->transactionBuilder()
    ->status(TransactionStatus::PENDING)
    ->type(TransactionType::PAYMENT)
    ->paymentMethod(PaymentMethod::CREDIT_CARD)
    ->amount(100.00, 'USD')
    ->gateway('stripe')
    ->gatewayTransactionId('ch_123456')
    ->metadata(['order_id' => 12345])
    ->save();

// Or use the static method
$transaction = TransactionBuilder::create()
    ->transactionable($order)
    ->status(TransactionStatus::PENDING)
    ->type(TransactionType::PAYMENT)
    ->paymentMethod(PaymentMethod::CREDIT_CARD)
    ->amount(100.00, 'USD')
    ->gateway('stripe')
    ->gatewayTransactionId('ch_123456')
    ->metadata(['order_id' => 12345])
    ->save();
```

### Querying Transactions

The package provides convenient scopes for filtering transactions:

```php
// Get all pending transactions
Transaction::pending()->get();

// Get completed transactions
Transaction::completed()->get();

// Get failed transactions
Transaction::failed()->get();

// Get cancelled transactions
Transaction::cancelled()->get();
```

### Checking Transaction Status

```php
$transaction->isPending();  
$transaction->isCompleted();
$transaction->isFailed();   
$transaction->isCancelled();
```

### Updating Transaction Status

```php
use Err0r\Laratransaction\Enums\TransactionStatus;

$transaction->markAsCompleted();
$transaction->markAsFailed();
$transaction->markAsCancelled();
```

Or set the status directly:

```php
$transaction->setStatus(TransactionStatus::COMPLETED);
$transaction->save();
```

### Updating Transaction Types

```php
use Err0r\Laratransaction\Enums\TransactionType;

$transaction->setType(TransactionType::REFUND);
$transaction->save();
```

### Updating Payment Methods

```php
use Err0r\Laratransaction\Enums\PaymentMethod;

$transaction->setPaymentMethod(PaymentMethod::BANK_TRANSFER);
$transaction->save();
```

### Accessing Related Models

```php
// Get all transactions for a model
$order->transactions;

// Access transaction details
$transaction->status;     // TransactionStatus model
$transaction->type;       // TransactionType model
$transaction->paymentMethod;  // PaymentMethod model
```

## Resource Classes
The package provides several resource classes to transform your models into JSON representations:
- [`TransactionResource`](src/Resources/TransactionResource.php): Transforms a transaction model.
- [`TransactionStatusResource`](src/Resources/TransactionStatusResource.php): Transforms a transaction status model.
- [`TransactionTypeResource`](src/Resources/TransactionTypeResource.php): Transforms a transaction type model.
- [`PaymentMethodResource`](src/Resources/PaymentMethodResource.php): Transforms a payment

## Testing
> TODO   

```bash
composer test
```

## Changelog
> TODO   

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
> TODO   

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities
> TODO   

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Faisal](https://github.com/err0r)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
