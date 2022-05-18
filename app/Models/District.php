<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;

    public function federalUnit() {
        return $this->belongsTo(FederalUnit::class);
    }
    public function ZipCodes() {
        return $this->hasMany(ZipCode::class);
    }
}
