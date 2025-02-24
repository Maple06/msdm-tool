<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nrp',
        'act_id'
    ];

    public function member() {
        return $this->belongsTo(Member::class, 'nrp', 'nrp');
    }

    public function activity() {
        return $this->belongsTo(Activity::class, 'act_id','id');
    }

}
