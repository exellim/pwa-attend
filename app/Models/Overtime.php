<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    //
    protected $table = 'attendances';
    protected $fillable = [
        'user_id',
        'status',
        'clock_in',
        'clock_in_photo',
        'clock_in_lat',
        'clock_in_long',
        'clock_out',
        'clock_out_photo',
        'clock_out_lat',
        'clock_out_long',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
