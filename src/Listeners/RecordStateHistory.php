<?php

namespace Norotaro\EnumataHistory\Listeners;

use Norotaro\Enumata\Events\TransitionedState;
use Norotaro\EnumataHistory\Models\StateHistory;

final class RecordStateHistory
{
    public function handle(TransitionedState $event): void
    {
        // $event->model->stateHistory()->create();
    }
}
