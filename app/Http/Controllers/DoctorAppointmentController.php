<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorAppointmentController extends Controller
{
    /**
     * Display a listing of appointments for a specific doctor.
     */
    public function index(Request $request): JsonResponse
    {
        Log::info('DoctorAppointment index called with request:', $request->all()); // للتشخيص

        $doctorId = $request->query('doctor_id');

        if (!$doctorId) {
            Log::warning('No doctor_id provided in request');
            return response()->json(['error' => 'doctor_id is required'], 422);
        }

        $appointments = Appointment::with([
            'doctor' => function ($query) {
                $query->select('id', 'name');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'image');
            }
        ])
        ->where('doctor_id', $doctorId)
        ->get();

        Log::info('Appointments retrieved for doctor_id: ' . $doctorId, ['count' => $appointments->count()]);

        return response()->json(['data' => $appointments], 200);
    }
}