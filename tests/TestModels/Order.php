<?php

namespace Norotaro\EnumataHistory\Tests\TestModels;

use Illuminate\Database\Eloquent\Model;
use Norotaro\Enumata\Traits\HasStateMachines;
use Norotaro\EnumataHistory\Traits\HasStateHistory;

class Order extends Model
{
    use HasStateMachines, HasStateHistory;

    protected $casts = [
        'status' => OrderStatus::class,
        'delivery_status' => OrderDeliveryStatus::class,
    ];
}
