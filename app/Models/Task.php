<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    //
    protected $table = 'tasks';
    protected $fillable = [
        'user_id',
        'task_name',
        'task_date',
        'task_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeUserTasks($query)
    {
        return $query->where('user_id', Auth::id());
    }

    public static function userCanAddTask($userId, $taskDate)
    {
        return Attendances::where('user_id', $userId)
                         ->whereDate('clock_in', $taskDate)
                         ->exists();
    }
}
