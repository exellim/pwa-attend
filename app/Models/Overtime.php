<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    //
    protected $table = 'overtimes';
    protected $fillable = [
        'user_id',
        'clock_in_ovt',
        'clock_in_ovt_photo',
        'clock_in_ovt_lat',
        'clock_in_ovt_long',
        'clock_out_ovt',
        'clock_out_ovt_photo',
        'clock_out_ovt_lat',
        'clock_out_ovt_long',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
