<?php

use Norotaro\EnumataRecorder\Models\StateLog;
use Norotaro\EnumataRecorder\Tests\TestModels\Order;
use Norotaro\EnumataRecorder\Tests\TestModels\OrderStatus;

beforeEach(function () {
    $model = new Order();
    $model->save();
    $model->pay();
    $model->end();
});

it('has accessors and mutators for from and to fields', function () {
    $lastLog = StateLog::orderBy('id', 'desc')->first();

    // check accessors
    expect($lastLog->from)->toBe(OrderStatus::Pending);
    expect($lastLog->to)->toBe(OrderStatus::Finished);

    // check mutators
    $lastLog->from = OrderStatus::Finished;
    $lastLog->to = OrderStatus::Pending;

    expect($lastLog->from)->toBe(OrderStatus::Finished);
    expect($lastLog->to)->toBe(OrderStatus::Pending);
});

it('add conditions with scopes', function () {
    $count = StateLog::fromState(OrderStatus::Pending)->count();

    expect($count)->toBe(1);

    $count = StateLog::toState(OrderStatus::Pending)->count();

    expect($count)->toBe(1);

    $count = StateLog::forField('status')->count();

    expect($count)->toBe(3);
});
