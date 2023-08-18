# Enumata History

[![Latest Version](https://img.shields.io/packagist/v/norotaro/enumata-history.svg?label=release)](https://packagist.org/packages/norotaro/enumata-history)
[![Tests](https://github.com/norotaro/enumata-history/actions/workflows/test.yaml/badge.svg)](https://github.com/norotaro/enumata-history/actions/workflows/test.yaml)

Automatically records all [Enumata](https://github.com/norotaro/enumata) transitions.

## Description

This package allow you to automatically record history of all states a model with [Enumata](https://github.com/norotaro/enumata) may have and query this history to take specific actions accordingly.

## Installation

Install the package via Composer:

```bash
composer require norotaro/enumata-history
```
Then run the migrations:

```bash
php artisan migrate
```

## Configuration

In a model configured to use **Enumata** ([see documentation](https://github.com/norotaro/enumata#basic-usage)) we only need to add the `HasStateHistory` trait:

```php
use Norotaro\Enumata\Traits\HasStateMachines;
use Norotaro\EnumataHistory\Traits\HasStateHistory;

class Order extends Model
{
    use HasStateMachines, HasStateHistory;

    protected $casts = [
        'status' => OrderStatus::class,
    ];
}
```

That's it. Now all transitions will be recorded automatically using the `state_history` table that was created when installing the package.

## Querying History

Get full history of transitioned states:

```php
$order->stateHistory;

// or

$order->stateHistory()->get();
```
The stateHistory() method returns an Eloquent relationship that can be chained as any [Query Builder](https://laravel.com/docs/10.x/queries) to further down the results. You also have some scopes available.

```php
$order->stateHistory()
    ->from(OrderStatus::Pending)
    ->to(OrderStatus::Approved)
    ->where('created_at', '<', Carbon::yesterday())
    ->get();
```

## Scopes

### `from($state)`

```php
$order->stateHistory()->from(OrderStatus::Pending)->get();
```
### `to($state)`

```php
$order->stateHistory()->to(OrderStatus::Approved)->get();
```
### `forField($field)`

This is util if you have more that one field that use states.

For example, having this model:

```php
use Norotaro\Enumata\Traits\HasStateMachines;

class Order extends Model
{
    use HasStateMachines;

    protected $casts = [
        'status'      => OrderStatus::class,
        'fulfillment' => OrderFulfillment::class,
    ];
}
```

We can access the history for each field in this way:

```php
$order->stateHistory()->forField('status')->get();

$order->stateHistory()->forField('fulfillment')->get();
```

Alternatively we can pass a param to `stateHistory()` with the name of the field to get the same result:

```php
$order->stateHistory('status')->get();

$order->stateHistory('fulfillment')->get();
```

## Testing

To run the test suite:

```php
composer run test
```

## Inspiration

This package was inspired by [asantibanez/laravel-eloquent-state-machines](https://github.com/asantibanez/laravel-eloquent-state-machines).

## LICENSE

The MIT License (MIT). Please see [License File](./LICENSE) for more information.
