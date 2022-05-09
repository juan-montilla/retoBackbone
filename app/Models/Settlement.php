<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $table = 'settlement';
    public $timestamps = false;
    protected $fillable = ['name','zone_type','settlement_type_id'];
    public function settlementType() {
        return $this->belongsTo(SettlementType::class);
    }
}
