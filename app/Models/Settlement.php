<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $table = 'settlements';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['name','zone_type','settlement_type_id','id'];
    public function settlementType() {
        return $this->belongsTo(SettlementType::class);
    }
    public function zipCodes() {
        $this->belongsToMany(ZipCode::class,'settlement_zipcode','settlement_id','zipcode_id');
    }
}
