<?php

namespace Norotaro\EnumataRecorder\Tests\TestModels;

use Illuminate\Database\Eloquent\Model;
use Norotaro\Enumata\Traits\HasStateMachines;
use Norotaro\EnumataRecorder\Traits\LogTransitions;

class Order extends Model
{
    use HasStateMachines, LogTransitions;

    protected $casts = [
        'status' => OrderStatus::class,
        'delivery_status' => OrderDeliveryStatus::class,
    ];
}
