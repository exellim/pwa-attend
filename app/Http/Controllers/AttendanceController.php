<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //
    public function clockIn()
    {
        return view('pages.attend.clock_in');
    }
}
