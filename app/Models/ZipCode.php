<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    use HasFactory;

    protected $table = 'zip_code';
    public $timestamps = false;
    protected $fillable = ['zip_code','locality','federal_entity_id','municipality_id'];
    public function settlements() {
        return $this->hasMany(Settlement::class);
    }
    public function federalEntity() {
        return $this->belongsTo(FederalEntity::class);
    }
    public function municipality() {
        return $this->belongsTo(Municipality::class);
    }
}
