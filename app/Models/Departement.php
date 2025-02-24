<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{

    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];

    public $incrementing = false;

    public function members() {
        return $this->hasMany(Member::class, 'departement_code', 'id');
    }

    public function divisions() {
        return $this->hasMany(Division::class,'departement_code','id');
    }
}
