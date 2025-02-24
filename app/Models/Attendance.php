<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'nrp',
        'participant_of',
        'volunteer_of',
        'status',
    ];

    public function participant() {
        return $this->belongsTo(Participant::class, 'participant_of','id');
    }

    public function member() {
        return $this->belongsTo(Member::class, 'nrp', 'nrp');
    }

    public function volunteer() {
        return $this->belongsTo(Volunteer::class, 'volunteer_of','id');
    }
}
