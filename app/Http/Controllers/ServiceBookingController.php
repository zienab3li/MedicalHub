<?php

namespace App\Http\Controllers;

use App\Models\ServiceBooking;
use Illuminate\Http\Request;

class ServiceBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServiceBooking::with(['service', 'user'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);

        // Prevent double booking for user at same date/time
        $alreadyBooked = ServiceBooking::where('user_id', $request->user_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->exists();

        if ($alreadyBooked) {
            return response()->json(['error' => 'You already have a booking at this time.'], 422);
        }

        $booking = ServiceBooking::create($request->all());

        return response()->json($booking, 201);
    }

    public function show($id)
    {
        return ServiceBooking::with(['service', 'user'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $booking = ServiceBooking::findOrFail($id);

        $booking->update($request->only([
            'appointment_date', 'appointment_time', 'status', 'address', 'phone'
        ]));

        return response()->json($booking);
    }

    public function destroy($id)
    {
        ServiceBooking::destroy($id);
        return response()->json(['message' => 'Booking deleted.']);
    }
}
