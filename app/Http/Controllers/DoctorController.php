<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index():JsonResponse
    // {
    //     $doctors = Doctor::with('clinic')->get();
    //     return response()->json(["data"=>$doctors],201);
    // }
    public function index(Request $request): JsonResponse
    {
        $query = Doctor::with('clinic');

        if ($request->has('role')) {
            $query->where('role', $request->role); // 'human' or 'vet'
        }

        $doctors = $query->get();
        return response()->json(["data" => $doctors], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|string|min:6|confirmed',
            'clinic_id' => 'required|exists:clinics,id',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
            'clinic_address' => 'nullable|string',
            'role' => 'required|in:human,vet',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('doctors', 'public');
            $data['image'] = $imagePath;
        }

        $doctor = Doctor::create($data);

        return response()->json(['data' => $doctor], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor): JsonResponse
    {
        return response()->json(['data' => $doctor->load('clinic')], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor) {}

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Doctor $doctor): JsonResponse
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:doctors,email,' . $doctor->id,
    //         'password' => 'sometimes|string|min:6|confirmed',
    //         'clinic_id' => 'required|exists:clinics,id',
    //         'specialization' => 'required|string',
    //         'bio' => 'nullable|string',
    //         'clinic_address' => 'nullable|string',
    //         'role' => 'sometimes|in:human,vet',
    //         'address' => 'nullable|string',
    //         'phone' => 'nullable|string',
    //         'image' => 'nullable|string',
    //     ]);

    //     if ($request->has('password')) {
    //         $request->merge(['password' => Hash::make($request->password)]);
    //     }

    //     $doctor->update($request->all());
    //     return response()->json(['data' => $doctor->load('clinic')], 200);
    // }


public function update(Request $request, Doctor $doctor): JsonResponse
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:doctors,email,' . $doctor->id,
        'password' => 'sometimes|string|min:6|confirmed',
        'clinic_id' => 'required|exists:clinics,id',
        'specialization' => 'required|string',
        'bio' => 'nullable|string',
        'clinic_address' => 'nullable|string',
        'role' => 'sometimes|in:human,vet',
        'address' => 'nullable|string',
        'phone' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
    ]);

    $data = $request->all();

    if ($request->has('password')) {
        $data['password'] = Hash::make($request->password);
    }

    if ($request->hasFile('image')) {
        if ($doctor->image && Storage::disk('public')->exists($doctor->image)) {
            Storage::disk('public')->delete($doctor->image);
        }

        $imagePath = $request->file('image')->store('doctors', 'public');
        $data['image'] = $imagePath;
    }

    $doctor->update($data);

    return response()->json(['data' => $doctor->load('clinic')], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor): JsonResponse
    {

        $doctor->delete();
        return response()->json(['message' => 'Doctor deleted successfully'], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);


        $doctor = Doctor::where('email', $request->email)->first();
        $token = $doctor->createToken('doctor-token')->plainTextToken;
        return response()->json(["message" => "Doctor logged in successfully", "data" => $doctor, "token" => $token], 200);
    }
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json(["message" => "Doctor logged out successfully"], 200);
    }
}
