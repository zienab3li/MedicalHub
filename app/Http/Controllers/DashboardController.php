<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function getStats()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalDoctors' => Doctor::count()
        ];

        return response()->json($stats);
    }
}
