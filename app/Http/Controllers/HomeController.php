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
        $user = Auth::user()->load('attendances', 'overtimes');

        // Get today's attendance record for the logged-in user
        $todayAttendance = $user->attendances()->whereDate('clock_in', Carbon::today())->first();

        // Check if the user has clocked in today and get status
        $hasClockedIn = !is_null($todayAttendance);
        $clockInStatus = $todayAttendance->status ?? 'Not Clocked In';

        // Check if the user has clocked out today
        $hasClockedOut = $hasClockedIn && $todayAttendance->clock_out !== null;

        // Get today's overtime record for the logged-in user
        $todayOvertime = $user->overtimes()->whereDate('clock_in_ovt', Carbon::today())->first();

        // Check if the user has clocked in for overtime
        $hasOvertimeIn = !is_null($todayOvertime);

        // Check if the user has clocked out for overtime
        $hasOvertimeOut = $hasOvertimeIn && $todayOvertime->clock_out_ovt !== null;

        // Get all users who have clocked in today
        $clockedInUsers = User::whereHas('attendances', function ($query) {
            $query->whereDate('clock_in', Carbon::today());
        })->with(['attendances' => function ($query) {
            $query->whereDate('clock_in', Carbon::today());
        }])->get();

        // Get all users who have clocked in for overtime today
        $overtimeUsers = User::whereHas('overtimes', function ($query) {
            $query->whereDate('clock_in_ovt', Carbon::today());
        })->with(['overtimes' => function ($query) {
            $query->whereDate('clock_in_ovt', Carbon::today());
        }])->get();

        // dd($overtimeUsers);
        return view('pages.home.index', compact(
            'user', 'hasClockedIn', 'hasClockedOut', 'clockInStatus',
            'hasOvertimeIn', 'hasOvertimeOut', 'clockedInUsers', 'overtimeUsers'
        ));
    }
}
