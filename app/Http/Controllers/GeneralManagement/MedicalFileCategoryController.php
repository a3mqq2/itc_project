<?php

namespace App\Http\Controllers\GeneralManagement;

use App\Http\Controllers\Controller;
use App\Models\MedicalFileCategory;
use Illuminate\Http\Request;

class MedicalFileCategoryController extends Controller
{
    public function index()
    {
        $categories = MedicalFileCategory::withCount('medicalFiles')->latest()->paginate(15);
        return view('general-management.medical-file-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('general-management.medical-file-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:medical_file_categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        MedicalFileCategory::create($validated);

        return redirect()->route('general-management.medical-file-categories.index')
            ->with('success', __('messages.category_created_successfully'));
    }

    public function edit(MedicalFileCategory $medicalFileCategory)
    {
        return view('general-management.medical-file-categories.edit', compact('medicalFileCategory'));
    }

    public function update(Request $request, MedicalFileCategory $medicalFileCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:medical_file_categories,name,' . $medicalFileCategory->id,
            'description' => 'nullable|string|max:500',
        ]);

        $medicalFileCategory->update($validated);

        return redirect()->route('general-management.medical-file-categories.index')
            ->with('success', __('messages.category_updated_successfully'));
    }

    public function destroy(MedicalFileCategory $medicalFileCategory)
    {
        $medicalFileCategory->delete();

        return redirect()->route('general-management.medical-file-categories.index')
            ->with('success', __('messages.category_deleted_successfully'));
    }
}
