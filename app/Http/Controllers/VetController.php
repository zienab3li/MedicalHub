<?php
namespace App\Http\Controllers;

use App\Models\Vet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $Vet_clinics = Vet::all();
        return response()->json(["data" => $Vet_clinics], 200); 
    }

    public function show(Vet $vet):JsonResponse
    {
        return response()->json(["data"=>$vet],201);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vets', 'public');
        }

        $vet = Vet::create($data);

        return response()->json(['data' => $vet], 201);
    }

    public function update(Request $request, Vet $vet): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($vet->image && Storage::disk('public')->exists($vet->image)) {
                Storage::disk('public')->delete($vet->image);
            }

            $data['image'] = $request->file('image')->store('vets', 'public');
        }

        $vet->update($data);

        return response()->json(['data' => $vet], 200);
    }

    public function destroy(Vet $vet):JsonResponse
    {
        $vet->delete();
        return response()->json(["message"=>"vet deleted"],200);
    }
}

