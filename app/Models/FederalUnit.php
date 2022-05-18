<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;

class FederalUnit extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;

    public function districts() {
        return $this->hasMany(District::class);
    }
    public function zipCodes() {
        return $this->hasMany(ZipCode::class, 'federal_unit_id','id');
    }
    public function scopeFindByName(Builder $query, string $name): Builder {
        return $query->where('name', $name);
    }
}
