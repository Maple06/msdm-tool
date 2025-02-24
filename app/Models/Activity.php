<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'nrp',
        'owned_by',
        'category',
        'must_attend',
    ];

    public function attendances() {
        return $this->hasMany(Attendance::class, 'act_id');
    }

    public function leader() {
        return $this->belongsTo(Member::class, 'nrp', 'nrp');
    }

    public function participant() {
        return $this->hasMany(Participant::class, 'act_id','id');
    }
}
