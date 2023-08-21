# Enumata Recorder

[![Latest Version](https://img.shields.io/packagist/v/norotaro/enumata-recorder.svg?label=release)](https://packagist.org/packages/norotaro/enumata-recorder)
[![Tests](https://github.com/norotaro/enumata-recorder/actions/workflows/test.yaml/badge.svg)](https://github.com/norotaro/enumata-recorder/actions/workflows/test.yaml)

Automatically records all [Enumata](https://github.com/norotaro/enumata) transitions.

## Description

This package allow you to automatically record logs of all states a model with [Enumata](https://github.com/norotaro/enumata) may have and query this logs to take specific actions accordingly.

## Installation

Install the package via Composer:

```bash
composer require norotaro/enumata-recorder
```
Then run the migrations:

```bash
php artisan migrate
```

## Configuration

In a model configured to use **Enumata** ([see documentation](https://github.com/norotaro/enumata#basic-usage)) we only need to add the `LogStates` trait:

```php
use Norotaro\Enumata\Traits\HasStateMachines;
use Norotaro\EnumataRecorder\Traits\LogStates;

class Order extends Model
{
    use HasStateMachines, LogStates;

    protected $casts = [
        'status' => OrderStatus::class,
    ];
}
```

That's it. Now all transitions will be recorded automatically using the `enumata_state_logs` table that was created when installing the package.

## Querying Logs

Get full history of transitioned states:

```php
$order->stateLogs;

// or

$order->stateLogs()->get();
```
The stateLogs() method returns an Eloquent relationship that can be chained as any [Query Builder](https://laravel.com/docs/10.x/queries) to further down the results. You also have some scopes available.

```php
$order->stateLogs()
    ->fromState(OrderStatus::Pending)
    ->toState(OrderStatus::Approved)
    ->where('created_at', '<', Carbon::yesterday())
    ->get();
```

## Scopes

### `fromState($state)`

```php
$order->stateLogs()->fromState(OrderStatus::Pending)->get();
```
### `toState($state)`

```php
$order->stateLogs()->toState(OrderStatus::Approved)->get();
```
### `forField($field)`

This is util if you have more that one field that use states.

For example, having this model:

```php
use Norotaro\Enumata\Traits\HasStateMachines;
use Norotaro\EnumataRecorder\Traits\LogStates;

class Order extends Model
{
    use HasStateMachines, LogStates;

    protected $casts = [
        'status'      => OrderStatus::class,
        'fulfillment' => OrderFulfillment::class,
    ];
}
```

We can access the logs for each field in this way:

```php
$order->stateLogs()->forField('status')->get();

$order->stateLogs()->forField('fulfillment')->get();
```

Alternatively we can pass a param to `stateLogs()` with the name of the field to get the same result:

```php
$order->stateLogs('status')->get();

$order->stateLogs('fulfillment')->get();
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
