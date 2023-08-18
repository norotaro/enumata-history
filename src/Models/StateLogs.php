<?php

namespace Norotaro\EnumataRecorder\Models;

use Illuminate\Database\Eloquent\Model;

class StateLogs extends Model
{
    protected $table = 'enumata_state_logs';

    protected $fillable = ['field', 'from', 'to'];
}
