<?php

namespace App\Http\Controllers;

use App\Models\DoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DoctorRequestController extends Controller
{
    /**
     * عرض كل طلبات الأطباء
     */
    public function index()
    {
        return response()->json(DoctorRequest::latest()->get());
    }

    /**
     * حفظ طلب جديد
     */
    public function store(Request $request)
{
    \Log::info('Doctor Request Received:', $request->all());
    \Log::info('Files:', $request->files->all());

    $validator = \Validator::make($request->all(), [
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'specialization' => 'required|string|max:255',
        'card_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'certificate_pdf' => 'required|file|mimes:pdf|max:5120',
        'notes' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        \Log::error('Validation Errors:', $validator->errors()->toArray());
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $data = $request->except(['card_image', 'certificate_pdf']);

    if ($request->hasFile('card_image') && $request->file('card_image')->isValid()) {
        try {
            $imagePath = $request->file('card_image')->store('doctor_cards', 'public');
            $data['card_image'] = 'storage/' . $imagePath;
            \Log::info('Card Image Uploaded:', ['path' => $data['card_image']]);
        } catch (\Exception $e) {
            \Log::error('Card Image Upload Failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'فشل في رفع صورة الكارنيه'], 500);
        }
    }

    if ($request->hasFile('certificate_pdf') && $request->file('certificate_pdf')->isValid()) {
        try {
            $pdfPath = $request->file('certificate_pdf')->store('doctor_certificates', 'public');
            $data['certificate_pdf'] = 'storage/' . $pdfPath;
            \Log::info('Certificate PDF Uploaded:', ['path' => $data['certificate_pdf']]);
        } catch (\Exception $e) {
            \Log::error('Certificate PDF Upload Failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'فشل في رفع الشهادة'], 500);
        }
    }

    $doctorRequest = DoctorRequest::create($data);

    return response()->json([
        'message' => 'تم إرسال الطلب بنجاح',
        'data' => $doctorRequest
    ], 201);
}
    /**
     * عرض طلب معين
     */
    public function show($id)
    {
        return response()->json(DoctorRequest::findOrFail($id));
    }

    /**
     * تعديل بيانات الطلب (ممكن لحالة المراجعة)
     */
    public function update(Request $request, $id)
    {
        $doctorRequest = DoctorRequest::findOrFail($id);

        $request->validate([
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'card_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'certificate_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'notes' => 'nullable|string',
            'is_read' => 'boolean',
        ]);

        $data = $request->only([
            'full_name', 'email', 'phone', 'specialization', 'notes', 'is_read'
        ]);

        // تحديث صورة الكارنيه
        if ($request->hasFile('card_image')) {
            $this->deleteFileIfExists($doctorRequest->card_image);
            $imagePath = $request->file('card_image')->store('doctor_cards', 'public');
            $data['card_image'] = 'storage/' . $imagePath;
        } elseif ($request->has('card_image') && $request->card_image === null) {
            $this->deleteFileIfExists($doctorRequest->card_image);
            $data['card_image'] = null;
        }

        // تحديث الشهادة PDF
        if ($request->hasFile('certificate_pdf')) {
            $this->deleteFileIfExists($doctorRequest->certificate_pdf);
            $pdfPath = $request->file('certificate_pdf')->store('doctor_certificates', 'public');
            $data['certificate_pdf'] = 'storage/' . $pdfPath;
        } elseif ($request->has('certificate_pdf') && $request->certificate_pdf === null) {
            $this->deleteFileIfExists($doctorRequest->certificate_pdf);
            $data['certificate_pdf'] = null;
        }

        $doctorRequest->update($data);

        return response()->json([
            'message' => 'تم تحديث الطلب',
            'data' => $doctorRequest
        ]);
    }

    /**
     * حذف الطلب
     */
    public function destroy($id)
    {
        $doctorRequest = DoctorRequest::findOrFail($id);

        $this->deleteFileIfExists($doctorRequest->card_image);
        $this->deleteFileIfExists($doctorRequest->certificate_pdf);

        $doctorRequest->delete();

        return response()->json(['message' => 'تم حذف الطلب بنجاح']);
    }

    /**
     * حذف أي ملف موجود
     */
    protected function deleteFileIfExists($filePath)
    {
        if ($filePath) {
            $path = str_replace('storage/', '', $filePath);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
