<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    //
    protected $table = 'attendances';
    protected $fillable = [
        'user',
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

    public function task()
    {
        return $this->hasMany(Task::class);
    }
}
