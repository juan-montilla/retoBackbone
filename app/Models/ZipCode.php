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
        return $this->hasMany(Settlement::class,'zip_code_id','code');
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
        $all = $this->settlements;
        $ret = [];
        foreach ($all as $settlement) {
            $ret[] = [
                'key'               => $settlement->id,
                'name'              => $settlement->name,
                'zone_type'         => $settlement->zone_type,
                'settlement_type'   => ['name'=>$settlement->settlementType->name],
            ];
        }
        return $ret;
    }
    public function formattedOutput():array {
        return [
            'zip_code'          => $this->code,
            'locality'          => $this->locality,
            'federal_entity'    => [
                'key'   => $this->federalUnit->id,
                'name'  => $this->federalUnit->name,
                'code'  => $this->federalUnit->code,
            ],
            'settlements'       => $this->resolvedSettlements(),
            'municipality'      => [
                'key'   => $this->district->id,
                'name'  => $this->district->name
            ],
        ];
    }
}
