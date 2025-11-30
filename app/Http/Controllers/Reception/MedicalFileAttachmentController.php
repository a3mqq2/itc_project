<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\MedicalFile;
use App\Models\MedicalFileAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MedicalFileAttachmentController extends Controller
{
    public function store(Request $request, MedicalFile $medicalFile)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('medical-files/' . $medicalFile->id, $fileName, 'public');

        MedicalFileAttachment::create([
            'medical_file_id' => $medicalFile->id,
            'file_name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
        ]);

        return redirect()->route('reception.medical-files.show', $medicalFile)
            ->with('success', __('messages.attachment_uploaded_successfully'));
    }

    public function update(Request $request, MedicalFile $medicalFile, MedicalFileAttachment $attachment)
    {
        $request->validate([
            'description' => 'nullable|string|max:500',
        ]);

        $attachment->update([
            'description' => $request->description,
        ]);

        return redirect()->route('reception.medical-files.show', $medicalFile)
            ->with('success', __('messages.attachment_updated_successfully'));
    }

    public function destroy(MedicalFile $medicalFile, MedicalFileAttachment $attachment)
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()->route('reception.medical-files.show', $medicalFile)
            ->with('success', __('messages.attachment_deleted_successfully'));
    }

    public function download(MedicalFile $medicalFile, MedicalFileAttachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()->route('reception.medical-files.show', $medicalFile)
                ->with('error', __('messages.file_not_found'));
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->original_name);
    }
}
