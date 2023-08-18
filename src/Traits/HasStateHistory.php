<?php

namespace Norotaro\EnumataHistory\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Norotaro\Enumata\Contracts\DefineStates;
use Norotaro\EnumataHistory\Models\StateHistory;
use UnitEnum;

trait HasStateHistory
{
    public static function bootHasStateHistory(): void
    {
        //TODO check that HasStateMachines trait is implemented in the model

        self::created([self::class, 'addStateHistory']);

        self::updated([self::class, 'addStateHistory']);
    }

    public function stateHistory(string $field = null): MorphMany
    {
        $query = $this->morphMany(StateHistory::class, 'model');

        if ($field) {
            $query->forField($field);
        }

        return $query;
    }

    public function scopeFrom(Builder $query, DefineStates&UnitEnum $state): void
    {
        $query->where('from', $state->name);
    }

    public function scopeTo(Builder $query, DefineStates&UnitEnum $state): void
    {
        $query->where('to', $state->name);
    }

    public function scopeForField(Builder $query, string $field): void
    {
        $query->where('field', $field);
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
