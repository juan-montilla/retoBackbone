<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ZipCode extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;

    public function settlements() {
        return $this->hasMany(Settlement::class,'code_id','code');
    }
    public function scopeFindByCode(Builder $query, string $code):Builder {
        return $query->where('code', $code);
    }
    public function district() {
        return $this->belongsTo(District::class);
    }
    public function federalUnit() {
        return $this->belongsTo(FederalUnit::class);
    }
    public function resolvedSettlements() {
        return $this->settlements->load('settlementType');
    }
    public function formattedOutput():array {
        return [
            'zip_code'          => $this->code,
            'locality'          => $this->locality,
            'federal_entity'    => $this->federalUnit,
            'settlements'       => $this->resolvedSettlements(),
            'municipality'      => $this->district,
        ];
    }
}
