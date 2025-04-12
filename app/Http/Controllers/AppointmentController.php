<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'user'])->get();
        return response()->json($appointments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string'
        ]);

        
        $doctorBooked = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->exists();

        if ($doctorBooked) {
            return response()->json(['error' => 'Doctor is already booked at this time.'], 422);
        }

        
        $patientHasAppointment = Appointment::where('user_id', $request->user_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->exists();

        if ($patientHasAppointment) {
            return response()->json(['error' => 'You already have an appointment at this time.'], 422);
        }

        $appointment = Appointment::create([
            'user_id' => $request->user_id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Appointment booked successfully.',
            'appointment' => $appointment
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = Appointment::with(['doctor', 'user'])->findOrFail($id);
        return response()->json($appointment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'status' => 'nullable|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($request->only(['status', 'notes']));

        return response()->json(['message' => 'Appointment updated.', 'appointment' => $appointment]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted.']);
    }
}
