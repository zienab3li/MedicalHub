<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Clinic;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\Service;
use App\Models\Vet;

class DashboardController extends Controller
{
    public function getStats()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalDoctors' => Doctor::count(),
            'totalAppointemnts' => Appointment::count(),
            'totalHumanClinic' => Clinic::count(),
            'totalVet' => Vet::count(),
            'totalService' => Service::count(),
            'totalFeedback' => Feedback::count(),
            'totalCategory' => Category::count(),
            'totalProduct' => Product::count()

        ];

        return response()->json($stats);
    }
}
