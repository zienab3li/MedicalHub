<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorAppointmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Log::info('DoctorAppointment index called with request:', $request->all());

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

    public function patients(Request $request): JsonResponse
    {
        Log::info('DoctorAppointment patients called with request:', $request->all());

        $doctorId = $request->query('doctor_id');

        if (!$doctorId) {
            Log::warning('No doctor_id provided in request');
            return response()->json(['error' => 'doctor_id is required'], 422);
        }

        $patients = User::select('id', 'name', 'image', 'phone', 'address', 'email', 'notes')
            ->whereIn('id', Appointment::where('doctor_id', $doctorId)
                ->pluck('user_id')
                ->unique())
            ->withCount(['appointments' => function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            }])
            ->with(['appointments' => function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId)
                      ->select('id', 'user_id', 'appointment_date', 'appointment_time')
                      ->orderBy('appointment_date', 'desc')
                      ->orderBy('appointment_time', 'desc')
                      ->take(1);
            }])
            ->get();

        Log::info('Patients retrieved for doctor_id: ' . $doctorId, ['count' => $patients->count()]);

        return response()->json(['data' => $patients], 200);
    }

    public function updatePatientNotes(Request $request, $patientId): JsonResponse
    {
        Log::info('Updating patient notes for patient ID: ' . $patientId, $request->all());

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $patient = User::findOrFail($patientId);
        $patient->notes = $request->input('notes');
        $patient->save();

        Log::info('Patient notes updated for patient ID: ' . $patientId);

        return response()->json(['data' => $patient], 200);
    }
}