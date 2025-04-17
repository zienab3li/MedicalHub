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
        'name' => 'nullable|string|min:5|max:150|regex:/^[A-Za-z\s]+$/|unique:services,name,',
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

    $validatedData = $request->validate([
        'name' => 'nullable|string|min:5|max:150|regex:/^[A-Za-z\s]+$/|unique:services,name,' . $id,
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        'price' => 'nullable|numeric',
        'duration' => 'nullable|integer',
        'is_active' => 'boolean',
        'instructions' => 'nullable|string',
    ]);

    $serviceData = $request->only([
        'name', 'description', 'price', 'duration', 'is_active', 'instructions'
    ]);

    if ($request->hasFile('image')) {
        try {
            // Delete old image if exists
            $this->deleteOldImage($service);

            $extension = $request->image->getClientOriginalExtension();
            $imageName = time() . '.' . $extension;
            $imagePath = $request->image->storeAs('service_images', $imageName, 'public');
            $serviceData['image'] = 'storage/' . $imagePath;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload image'], 500);
        }
    } elseif ($request->has('image') && $request->image === null) {
        // Allow clearing the image
        $this->deleteOldImage($service);
        $serviceData['image'] = null;
    }

    $service->update($serviceData);

    return response()->json([
        'message' => 'Service updated successfully',
        'data' => $service
    ], 200);
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
