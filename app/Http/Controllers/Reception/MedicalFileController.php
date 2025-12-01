<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\MedicalFile;
use App\Models\MedicalFileCategory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\MedicalFilesExport;
use App\Imports\MedicalFilesImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalFileController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalFile::with(['husband', 'wife', 'category']);

        // Apply filters
        if ($request->filled('file_number')) {
            $query->where('file_number', 'like', '%' . $request->file_number . '%');
        }

        if ($request->filled('region')) {
            $query->where('region', 'like', '%' . $request->region . '%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('registration_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('registration_date', '<=', $request->to_date);
        }

        if ($request->filled('patient_name')) {
            $query->whereHas('patients', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        $medicalFiles = $query->latest()->paginate(15);

        return view('reception.medical-files.index', compact('medicalFiles'));
    }

    public function create()
    {
        // Get next file number
        $lastFile = MedicalFile::latest('id')->first();
        $nextFileNumber = $lastFile ? ($lastFile->file_number + 1) : 1;

        // Get categories
        $categories = MedicalFileCategory::orderBy('name')->get();

        return view('reception.medical-files.create', compact('nextFileNumber', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_number' => 'required|string|unique:medical_files,file_number',
            'category_id' => 'nullable|exists:medical_file_categories,id',
            'region' => 'nullable|string|max:255',
            'diagnosis' => 'nullable|string',
            'registration_date' => 'required|date',

            // Husband data
            'husband_name' => 'required|string|max:255',
            'husband_national_id' => 'required|string|max:255',
            'husband_registry_number' => 'required|string|max:255',
            'husband_dob' => 'required|date',

            // Wife data
            'wife_name' => 'required|string|max:255',
            'wife_national_id' => 'required|string|max:255',
            'wife_registry_number' => 'required|string|max:255',
            'wife_dob' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Create medical file
            $medicalFile = MedicalFile::create([
                'file_number' => $validated['file_number'],
                'category_id' => $validated['category_id'] ?? null,
                'region' => $validated['region'],
                'diagnosis' => $validated['diagnosis'],
                'registration_date' => $validated['registration_date'],
            ]);

            // Create husband patient
            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'husband',
                'name' => $validated['husband_name'],
                'national_id' => $validated['husband_national_id'],
                'registry_number' => $validated['husband_registry_number'],
                'dob' => $validated['husband_dob'],
            ]);

            // Create wife patient
            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'wife',
                'name' => $validated['wife_name'],
                'national_id' => $validated['wife_national_id'],
                'registry_number' => $validated['wife_registry_number'],
                'dob' => $validated['wife_dob'],
            ]);

            DB::commit();

            return redirect()->route('reception.medical-files.index')
                ->with('success', __('messages.medical_file_created_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(MedicalFile $medicalFile)
    {
        $medicalFile->load(['husband', 'wife', 'category']);
        return view('reception.medical-files.show', compact('medicalFile'));
    }

    public function edit(MedicalFile $medicalFile)
    {
        $medicalFile->load(['husband', 'wife']);
        $categories = MedicalFileCategory::orderBy('name')->get();
        return view('reception.medical-files.edit', compact('medicalFile', 'categories'));
    }

    public function update(Request $request, MedicalFile $medicalFile)
    {
        $validated = $request->validate([
            'file_number' => 'required|string|unique:medical_files,file_number,' . $medicalFile->id,
            'category_id' => 'nullable|exists:medical_file_categories,id',
            'region' => 'nullable|string|max:255',
            'diagnosis' => 'nullable|string',
            'registration_date' => 'required|date',

            // Husband data
            'husband_name' => 'required|string|max:255',
            'husband_national_id' => 'required|string|max:255',
            'husband_registry_number' => 'required|string|max:255',
            'husband_dob' => 'required|date',

            // Wife data
            'wife_name' => 'required|string|max:255',
            'wife_national_id' => 'required|string|max:255',
            'wife_registry_number' => 'required|string|max:255',
            'wife_dob' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Update medical file
            $medicalFile->update([
                'file_number' => $validated['file_number'],
                'category_id' => $validated['category_id'] ?? null,
                'region' => $validated['region'],
                'diagnosis' => $validated['diagnosis'],
                'registration_date' => $validated['registration_date'],
            ]);

            // Update husband
            $medicalFile->husband()->update([
                'name' => $validated['husband_name'],
                'national_id' => $validated['husband_national_id'],
                'registry_number' => $validated['husband_registry_number'],
                'dob' => $validated['husband_dob'],
            ]);

            // Update wife
            $medicalFile->wife()->update([
                'name' => $validated['wife_name'],
                'national_id' => $validated['wife_national_id'],
                'registry_number' => $validated['wife_registry_number'],
                'dob' => $validated['wife_dob'],
            ]);

            DB::commit();

            return redirect()->route('reception.medical-files.index')
                ->with('success', __('messages.medical_file_updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(MedicalFile $medicalFile)
    {
        $medicalFile->delete();

        return redirect()->route('reception.medical-files.index')
            ->with('success', __('messages.medical_file_deleted_successfully'));
    }

    public function print(MedicalFile $medicalFile)
    {
        $medicalFile->load(['husband', 'wife']);
        return view('reception.medical-files.print', compact('medicalFile'));
    }

    public function exportPdf(MedicalFile $medicalFile)
    {
        $medicalFile->load(['husband', 'wife']);

        $pdf = Pdf::loadView('reception.medical-files.print', compact('medicalFile'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('medical-file-' . $medicalFile->file_number . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['file_number', 'region', 'from_date', 'to_date', 'patient_name']);

        return Excel::download(
            new MedicalFilesExport($filters),
            'medical-files-' . date('Y-m-d-H-i-s') . '.xlsx'
        );
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new MedicalFilesImport, $request->file('file'));

            return redirect()->route('reception.medical-files.index')
                ->with('success', __('messages.medical_files_imported_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            [
                __('messages.file_number'),
                __('messages.region'),
                __('messages.diagnosis'),
                __('messages.registration_date'),
                __('messages.husband') . ' - ' . __('messages.name'),
                __('messages.husband') . ' - ' . __('messages.national_id'),
                __('messages.husband') . ' - ' . __('messages.registry_number'),
                __('messages.husband') . ' - ' . __('messages.date_of_birth'),
                __('messages.wife') . ' - ' . __('messages.name'),
                __('messages.wife') . ' - ' . __('messages.national_id'),
                __('messages.wife') . ' - ' . __('messages.registry_number'),
                __('messages.wife') . ' - ' . __('messages.date_of_birth'),
            ]
        ];

        return Excel::download(
            new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
                protected $data;
                public function __construct($data) { $this->data = $data; }
                public function array(): array { return $this->data; }
            },
            'medical-files-template.xlsx'
        );
    }
}
