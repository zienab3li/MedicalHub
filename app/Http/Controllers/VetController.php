<?php

namespace App\Http\Controllers;

use App\Models\Vet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VetController extends Controller
{
    public function index(Request $request){
        $Vet_clinics= Vet::all();
        return response()->json(["data"=>$Vet_clinics],201);
    }
    public function store(Request $request): JsonResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|string', 
    ]);

    $vet = Vet::create($request->all());

    return response()->json(['data' => $vet], 201);
}

public function update(Request $request, Vet $vet): JsonResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|string',
    ]);

    $vet->update($request->all());

    return response()->json(['data' => $vet], 200);
}


}
