<?php

namespace Norotaro\EnumataRecorder\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Norotaro\Enumata\Contracts\DefineStates;
use UnitEnum;

class StateLogs extends Model
{
    protected $table = 'enumata_state_logs';

    protected $fillable = ['field', 'from', 'to'];

    public function from(): Attribute
    {
        return new Attribute(
            get: fn (?string $value) => $value ? $this->getStateFromString($value) : null,
            set: fn (?UnitEnum $value) => $value?->name,
        );
    }

    public function to(): Attribute
    {
        return new Attribute(
            get: fn (?string $value) => $value ? $this->getStateFromString($value) : null,
            set: fn (?UnitEnum $value) => $value?->name,
        );
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

    protected function getStateFromString(string $value): ?UnitEnum
    {
        $model = new $this->model_type;
        $enumName = $model->getCasts()[$this->field] . '::' . $value;

        return defined($enumName) ? constant($enumName) : null;
    }
}
