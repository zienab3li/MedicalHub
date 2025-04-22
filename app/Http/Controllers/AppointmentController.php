<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
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
        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        if ($appointmentDateTime->lt(Carbon::now())) {
            return response()->json(['error' => 'You cannot book an appointment in the past.'], 422);
        }
        
        $requestedDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        $startBuffer = $requestedDateTime->copy()->subMinutes(30)->format('H:i:s');
        $endBuffer = $requestedDateTime->copy()->addMinutes(30)->format('H:i:s');
        
        $doctorBooked = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->whereBetween('appointment_time', [$startBuffer, $endBuffer])
            ->exists();
        
        if ($doctorBooked) {
            return response()->json(['error' => 'Doctor has another appointment too close to this time. Please choose a different slot.'], 422);
        }
        

        
        $patientHasAppointment = Appointment::where('user_id', $request->user_id)
        ->where('appointment_date', $request->appointment_date)
        ->whereBetween('appointment_time', [$startBuffer, $endBuffer])
        ->exists();
    
    if ($patientHasAppointment) {
        return response()->json(['error' => 'You have another appointment too close to this time.'], 422);
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
