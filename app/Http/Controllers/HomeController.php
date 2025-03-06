<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user()->load('attendances');

        // Get today's attendance record for the logged-in user
        $todayAttendance = $user->attendances()->whereDate('clock_in', Carbon::today())->first();

        // Check if the user has clocked in today and get status
        $hasClockedIn = !is_null($todayAttendance);
        $clockInStatus = $todayAttendance->status ?? 'Not Clocked In';

        // Check if user has clocked out today
        $hasClockedOut = $hasClockedIn && $todayAttendance->clock_out !== null;

        // Get all users who have clocked in today
        $clockedInUsers = User::whereHas('attendances', function ($query) {
            $query->whereDate('clock_in', Carbon::today());
        })->with(['attendances' => function ($query) {
            $query->whereDate('clock_in', Carbon::today());
        }])->get();

        return view('pages.home.index', compact('user', 'hasClockedIn', 'hasClockedOut', 'clockInStatus', 'clockedInUsers'));
    }
}
