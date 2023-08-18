<?php

use Norotaro\EnumataRecorder\Tests\TestModels\Order;
use Norotaro\EnumataRecorder\Tests\TestModels\OrderStatus;

beforeEach(function () {
    $this->model = new Order();
});

it('save log when the model is created', function () {
    $this->model->save();

    expect($this->model->stateLogs->count())->toBe(1);
    expect($this->model->stateLogs->first()->field)->toBe('status');
    expect($this->model->stateLogs->first()->from)->toBeNull();
    expect($this->model->stateLogs->first()->to)->toBe(OrderStatus::Default->name);
});

it('save logs when the model is updated', function () {
    $this->model->save();
    $this->model->pay();
    $logs = $this->model->stateLogs;

    expect($logs->count())->toBe(2);
    expect($logs->last()->field)->toBe('status');
    expect($logs->last()->from)->toBe(OrderStatus::Default->name);
    expect($logs->last()->to)->toBe(OrderStatus::Pending->name);

    $this->model->end();
    // reload logs
    $logs = $this->model->stateLogs()->get();

    expect($logs->count())->toBe(3);
    expect($logs->last()->field)->toBe('status');
    expect($logs->last()->from)->toBe(OrderStatus::Pending->name);
    expect($logs->last()->to)->toBe(OrderStatus::Finished->name);
});

it('save logs when transitions are forced', function () {
    $this->model->save();
    $this->model->initState();
    $this->model->isPending();

    expect($this->model->stateLogs->count())->toBe(3);

    $this->model->initState(force: true);

    // reload history
    $this->model->setRelations([]);

    expect($this->model->stateLogs->count())->toBe(4);
});

it('does not save a log if the model does not change the state', function () {
    $this->model->save();
    $this->model->updated_at = now();
    $this->model->save();

    expect($this->model->stateLogs->count())->toBe(1);

    $this->model->status()->transitionTo(OrderStatus::Default);

    expect($this->model->stateLogs->count())->toBe(1);
});
