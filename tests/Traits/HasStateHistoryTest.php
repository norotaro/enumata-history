<?php

use Norotaro\EnumataHistory\Tests\TestModels\Order;
use Norotaro\EnumataHistory\Tests\TestModels\OrderStatus;

beforeEach(function () {
    $this->model = new Order();
});

it('save history when the model is created', function () {
    $this->model->save();

    expect($this->model->stateHistory->count())->toBe(1);
    expect($this->model->stateHistory->first()->field)->toBe('status');
    expect($this->model->stateHistory->first()->from)->toBeNull();
    expect($this->model->stateHistory->first()->to)->toBe(OrderStatus::Default->name);
});

it('save history when the model is updated', function () {
    $this->model->save();
    $this->model->pay();
    $history = $this->model->stateHistory;

    expect($history->count())->toBe(2);
    expect($history->last()->field)->toBe('status');
    expect($history->last()->from)->toBe(OrderStatus::Default->name);
    expect($history->last()->to)->toBe(OrderStatus::Pending->name);

    $this->model->end();
    // reload history
    $history = $this->model->stateHistory()->get();

    expect($history->count())->toBe(3);
    expect($history->last()->field)->toBe('status');
    expect($history->last()->from)->toBe(OrderStatus::Pending->name);
    expect($history->last()->to)->toBe(OrderStatus::Finished->name);
});

it('save history when transitions are forced', function () {
    $this->model->save();
    $this->model->initState();
    $this->model->isPending();

    expect($this->model->stateHistory->count())->toBe(3);

    $this->model->initState(force: true);

    // reload history
    $this->model->setRelations([]);

    expect($this->model->stateHistory->count())->toBe(4);
});

it('does not save a history if the model does not change the state', function () {
    $this->model->save();
    $this->model->updated_at = now();
    $this->model->save();

    expect($this->model->stateHistory->count())->toBe(1);

    $this->model->status()->transitionTo(OrderStatus::Default);

    expect($this->model->stateHistory->count())->toBe(1);
});
