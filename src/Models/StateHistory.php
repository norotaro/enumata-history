<?php

namespace Norotaro\EnumataHistory\Models;

use Illuminate\Database\Eloquent\Model;

class StateHistory extends Model
{
    protected $table = 'enumata_state_history';

    protected $fillable = ['field', 'from', 'to'];
}
