<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SettlementType extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function scopeFindByName(Builder $query, string $name): Builder {
        return $query->where('name', $name);
    }
    public function settlements() {
        return $this->hasMany(Settlements::class);
    }
}
