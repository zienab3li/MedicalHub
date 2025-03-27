<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;



class PrescriptionController extends Controller
{
    //  upload Prescription
    public function uploadPrescription(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,png,pdf|max:2048', 
        ]);

        $filePath = $request->file('file')->store('prescriptions', 'public');

        $userId = Auth::id();
        $prescription = Prescription::create([
            'user_id' => $userId,
            'file_path' => $filePath,
            'status' => 'pending'
        ]);

        return response()->json([
            'status'=> 201,
            'message' => 'prescription updloaded successfully',
            'data' => $prescription
        ]);
    }
}
