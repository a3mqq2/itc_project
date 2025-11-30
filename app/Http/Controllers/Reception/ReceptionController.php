<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\MedicalFile;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReceptionController extends Controller
{
    public function home()
    {
        // Medical Files Statistics
        $totalFiles = MedicalFile::count();
        $filesThisMonth = MedicalFile::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Files by Region
        $filesByRegion = MedicalFile::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->pluck('total', 'region')
            ->toArray();

        // Files by Month (Last 12 months)
        $filesByMonth = MedicalFile::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Files by Category
        $filesByCategory = MedicalFile::select('medical_file_categories.name', DB::raw('count(*) as total'))
            ->leftJoin('medical_file_categories', 'medical_files.category_id', '=', 'medical_file_categories.id')
            ->groupBy('medical_file_categories.id', 'medical_file_categories.name')
            ->pluck('total', 'name')
            ->toArray();

        // Appointments Statistics
        $totalAppointments = Appointment::count();
        $appointmentsThisMonth = Appointment::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Appointments by Month (Last 12 months)
        $appointmentsByMonth = Appointment::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Appointments by Category
        $appointmentsByCategory = Appointment::select('medical_file_categories.name', DB::raw('count(*) as total'))
            ->join('medical_files', 'appointments.medical_file_id', '=', 'medical_files.id')
            ->leftJoin('medical_file_categories', 'medical_files.category_id', '=', 'medical_file_categories.id')
            ->groupBy('medical_file_categories.id', 'medical_file_categories.name')
            ->pluck('total', 'name')
            ->toArray();

        // Today's Appointments
        $todayAppointments = Appointment::with(['medicalFile.husband', 'medicalFile.wife'])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        return view('reception.home', compact(
            'totalFiles',
            'filesThisMonth',
            'filesByRegion',
            'filesByMonth',
            'filesByCategory',
            'totalAppointments',
            'appointmentsThisMonth',
            'appointmentsByMonth',
            'appointmentsByCategory',
            'todayAppointments'
        ));
    }

    public function searchFile(Request $request)
    {
        $request->validate([
            'file_number' => 'required|string'
        ]);

        $medicalFile = MedicalFile::where('file_number', $request->file_number)->first();

        if (!$medicalFile) {
            return response()->json([
                'success' => false,
                'message' => __('messages.medical_file_not_found')
            ], 404);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('reception.medical-files.show', $medicalFile->id)
        ]);
    }
}
