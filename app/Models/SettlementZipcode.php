<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementZipcode extends Model
{
    use HasFactory;
    protected $table = 'settlement_zipcode';
    public $timestamps = false;
    protected $fillable = ['settlement_id','zipcode_id'];
}
