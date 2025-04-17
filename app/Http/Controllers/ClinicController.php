<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $clinics= Clinic::all();
        return response()->json(["data"=>$clinics],201);
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
    // public function store(Request $request):JsonResponse
    // {
    //     $request->validate([
    //         'name'=>'required|string|max:255',
    //         'description'=>'required|string|max:255'
    //     ]);
    //     $clinic=Clinic::create($request->all());
    //     return response()->json(["data"=>$clinic],201);
    // }

    public function store(Request $request): JsonResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('clinics', 'public');
    }

    $clinic = Clinic::create($data);

    return response()->json(["data" => $clinic], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Clinic $clinic):JsonResponse
    {
        return response()->json(["data"=>$clinic],201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Clinic $clinic):JsonResponse
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Clinic $clinic):JsonResponse
    // {
    //     $request->validate([
    //         'name'=>'required|string|max:255',
    //         'description'=>'required|string|max:255'
    //     ]);
    //     $clinic->update($request->all());
    //     return response()->json(["data"=>$clinic],200);
    // }

    public function update(Request $request, Clinic $clinic): JsonResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        if ($clinic->image && Storage::disk('public')->exists($clinic->image)) {
            Storage::disk('public')->delete($clinic->image);
        }

        $data['image'] = $request->file('image')->store('clinics', 'public');
    }

    $clinic->update($data);

    return response()->json(['data' => $clinic], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic):JsonResponse
    {
        $clinic->delete();
        return response()->json(["message"=>"clinic deleted"],200);
    }
}
