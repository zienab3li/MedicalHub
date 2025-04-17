<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Service::where('is_active', true)->get());
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|min:5|max:150|regex:/^[A-Za-z\s]+$/|unique:services,name,',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        'price' => 'nullable|numeric',
        'duration' => 'nullable|integer',
        'is_active' => 'boolean',
        'instructions' => 'nullable|string',
    ]);

    $serviceData = $request->except('image');

    if ($request->hasFile('image')) {
        try {
            $extension = $request->image->getClientOriginalExtension();
            $imageName = time() . '.' . $extension;
            $imagePath = $request->image->storeAs('service_images', $imageName, 'public');
            $serviceData['image'] = 'storage/' . $imagePath;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload image'], 500);
        }
    } else {
        $serviceData['image'] = null;
    }

    $service = Service::create($serviceData);

    return response()->json([
        'message' => 'Service added successfully',
        'data' => $service
    ], 201);
}


    public function show($id)
    {
        return response()->json(Service::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $service->update($request->only([
            'name', 'description', 'image', 'price', 'duration', 'is_active', 'instructions'
        ]));

        return response()->json($service);
    }

    
    

    public function destroy(Service $service)
    {
        $this->deleteOldImage($service);
        $service->delete();
        return response()->json(['message' => 'Service deleted.']);
    }
    protected function deleteOldImage($service){
        if($service->image){
            $oldImagePath = str_replace('storage/', '', $service->image);
            if(Storage::disk('public')->exists($oldImagePath)){
                Storage::disk('public')->delete($oldImagePath);
            }
        }
    }
    
}
