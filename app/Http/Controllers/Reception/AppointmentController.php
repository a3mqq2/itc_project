<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalFile;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['medicalFile.husband', 'medicalFile.wife', 'medicalFile.category']);

        // Apply filters
        if ($request->filled('file_number')) {
            $query->whereHas('medicalFile', function ($q) use ($request) {
                $q->where('file_number', 'like', '%' . $request->file_number . '%');
            });
        }

        if ($request->filled('patient_name')) {
            $query->whereHas('medicalFile.patients', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $appointments = $query->latest()->paginate(15);

        return view('reception.appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('reception.appointments.create');
    }

    public function getMedicalFile(Request $request)
    {
        $request->validate([
            'file_number' => 'required|string'
        ]);

        $medicalFile = MedicalFile::with(['husband', 'wife'])
            ->where('file_number', $request->file_number)
            ->first();

        if (!$medicalFile) {
            return response()->json([
                'success' => false,
                'message' => __('messages.medical_file_not_found')
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $medicalFile->id,
                'file_number' => $medicalFile->file_number,
                'husband' => [
                    'name' => $medicalFile->husband?->name,
                    'national_id' => $medicalFile->husband?->national_id,
                ],
                'wife' => [
                    'name' => $medicalFile->wife?->name,
                    'national_id' => $medicalFile->wife?->national_id,
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'medical_file_id' => 'required|exists:medical_files,id',
            'include_husband' => 'nullable|boolean',
            'include_wife' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        // Ensure at least one patient is selected
        if (!($validated['include_husband'] ?? false) && !($validated['include_wife'] ?? false)) {
            return back()->withInput()->with('error', __('messages.select_at_least_one_patient'));
        }

        Appointment::create([
            'medical_file_id' => $validated['medical_file_id'],
            'include_husband' => $validated['include_husband'] ?? false,
            'include_wife' => $validated['include_wife'] ?? false,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('reception.appointments.index')
            ->with('success', __('messages.appointment_created_successfully'));
    }

    public function edit(Appointment $appointment)
    {
        $appointment->load(['medicalFile.husband', 'medicalFile.wife']);
        return view('reception.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'include_husband' => 'nullable|boolean',
            'include_wife' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        // Ensure at least one patient is selected
        if (!($validated['include_husband'] ?? false) && !($validated['include_wife'] ?? false)) {
            return back()->withInput()->with('error', __('messages.select_at_least_one_patient'));
        }

        $appointment->update([
            'include_husband' => $validated['include_husband'] ?? false,
            'include_wife' => $validated['include_wife'] ?? false,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('reception.appointments.index')
            ->with('success', __('messages.appointment_updated_successfully'));
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('reception.appointments.index')
            ->with('success', __('messages.appointment_deleted_successfully'));
    }

    public function printList(Request $request)
    {
        $query = Appointment::with(['medicalFile.husband', 'medicalFile.wife', 'medicalFile.category']);

        // Apply same filters as index
        if ($request->filled('file_number')) {
            $query->whereHas('medicalFile', function ($q) use ($request) {
                $q->where('file_number', 'like', '%' . $request->file_number . '%');
            });
        }

        if ($request->filled('patient_name')) {
            $query->whereHas('medicalFile.patients', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $appointments = $query->latest()->get();

        return view('reception.appointments.print-list', compact('appointments'));
    }
}
