<?php

namespace Norotaro\EnumataHistory\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Norotaro\EnumataHistory\Models\StateHistory;

trait HasStateHistory
{
    public static function bootHasStateHistory(): void
    {
        //TODO check that HasStateMachines trait is implemented in the model

        self::created([self::class, 'addStateHistory']);

        self::updated([self::class, 'addStateHistory']);
    }

    public function stateHistory(): MorphMany
    {
        return $this->morphMany(StateHistory::class, 'model');
    }

    public static function addStateHistory(Model $model)
    {
        $newHistory = [];

        /** @var \Norotaro\Enumata\StateMachine */
        foreach ($model->getStateMachines() as $stateMachine) {
            $currentState = $stateMachine->currentState();
            $originalState = $model->getOriginal($stateMachine->getField());

            if (is_null($currentState) || $originalState === $currentState) {
                continue;
            }

            $newHistory[] = [
                'field' => $stateMachine->getField(),
                'from'  => $originalState?->name,
                'to'    => $currentState->name
            ];
        }

        if (count($newHistory)) {
            $model->stateHistory()->createMany($newHistory);
        }
    }
}
