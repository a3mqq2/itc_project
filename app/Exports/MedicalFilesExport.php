<?php

namespace App\Exports;

use App\Models\MedicalFile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MedicalFilesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = MedicalFile::with(['husband', 'wife']);

        // Apply filters
        if (!empty($this->filters['file_number'])) {
            $query->where('file_number', 'like', '%' . $this->filters['file_number'] . '%');
        }

        if (!empty($this->filters['region'])) {
            $query->where('region', 'like', '%' . $this->filters['region'] . '%');
        }

        if (!empty($this->filters['from_date'])) {
            $query->whereDate('registration_date', '>=', $this->filters['from_date']);
        }

        if (!empty($this->filters['to_date'])) {
            $query->whereDate('registration_date', '<=', $this->filters['to_date']);
        }

        if (!empty($this->filters['patient_name'])) {
            $query->whereHas('patients', function ($q) {
                $q->where('name', 'like', '%' . $this->filters['patient_name'] . '%');
            });
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
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
        ];
    }

    public function map($medicalFile): array
    {
        return [
            $medicalFile->file_number,
            $medicalFile->region,
            $medicalFile->diagnosis,
            $medicalFile->registration_date->format('Y-m-d'),
            $medicalFile->husband?->name,
            $medicalFile->husband?->national_id,
            $medicalFile->husband?->registry_number,
            $medicalFile->husband?->dob?->format('Y-m-d'),
            $medicalFile->wife?->name,
            $medicalFile->wife?->national_id,
            $medicalFile->wife?->registry_number,
            $medicalFile->wife?->dob?->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 30,
            'D' => 15,
            'E' => 25,
            'F' => 20,
            'G' => 20,
            'H' => 15,
            'I' => 25,
            'J' => 20,
            'K' => 20,
            'L' => 15,
        ];
    }
}
