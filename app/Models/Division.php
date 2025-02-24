<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'departement_code'
    ];

    public $incrementing = false;

    public function members() {
        return $this->hasMany(Member::class, 'division_code', 'id');
    }

    public function departement() {
        return $this->belongsTo(Departement::class,'departement_code','id');
    }
}
