# Laravel Payment Transactions Helper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/err0r/laratransaction.svg?style=flat-square)](https://packagist.org/packages/err0r/laratransaction)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/err0r/laratransaction/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/err0r/laratransaction/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/err0r/laratransaction/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/err0r/laratransaction/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/err0r/laratransaction.svg?style=flat-square)](https://packagist.org/packages/err0r/laratransaction)

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
- Multi-currency support 
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

Use the fluent `TransactionBuilder` to create transactions:

```php
use Err0r\Laratransaction\Builders\TransactionBuilder;
use Err0r\Laratransaction\Enums\TransactionStatus;
use Err0r\Laratransaction\Enums\TransactionType;
use Err0r\Laratransaction\Enums\PaymentMethod;

// Create a transaction for an order
$order = Order::find(1);

$transaction = TransactionBuilder::create()
    ->transactionable($order)
    ->status(TransactionStatus::PENDING)
    ->type(TransactionType::PAYMENT)
    ->paymentMethod(PaymentMethod::CREDIT_CARD)
    ->amount(100.00, 'USD')
    ->gateway('stripe')
    ->gatewayTransactionId('ch_123456')
    ->metadata(['order_id' => 12345])
    ->build();

$transaction->save();
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
$transaction->isPending();    // Check if pending
$transaction->isCompleted();  // Check if completed
$transaction->isFailed();     // Check if failed
$transaction->isCancelled();  // Check if cancelled
```

### Updating Transaction Status

```php
use Err0r\Laratransaction\Enums\TransactionStatus;

$transaction->setStatus(TransactionStatus::COMPLETED);
$transaction->save();
```

### Accessing Related Models

```php
// Get all transactions for a model
$order->transactions;

// Get the latest transaction
$order->latestTransaction;

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
