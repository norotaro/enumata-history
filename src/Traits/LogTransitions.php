<?php

namespace Norotaro\EnumataRecorder\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Norotaro\EnumataRecorder\Models\StateLog;
use UnitEnum;

trait LogTransitions
{
    public static function bootLogTransitions(): void
    {
        //TODO check that HasStateMachines trait is implemented in the model

        self::created([self::class, 'addStateLogs']);

        self::updated([self::class, 'addStateLogs']);
    }

    public function stateLogs(string $field = null): MorphMany
    {
        $query = $this->morphMany(StateLog::class, 'model');

        if ($field) {
            $query->forField($field);
        }

        return $query;
    }

    public static function addStateLogs(Model $model)
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
                'from'  => $originalState,
                'to'    => $currentState
            ];
        }

        if (count($newHistory)) {
            $model->stateLogs()->createMany($newHistory);
        }
    }
}
