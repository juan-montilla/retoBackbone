<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;

    public function settlementType() {
        return $this->belongsTo(SettlementType::class);
    }
    public function zipCode() {
        return $this->belongsTo(ZipCode::class,'code');
    }
}
