<?php

namespace App\Imports;

use App\Models\MedicalFile;
use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicalFilesImport implements ToModel, WithHeadingRow, WithValidation
{
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // If it's just a year (4 digits), convert to January 1st of that year
        if (is_numeric($value) && strlen((string)$value) === 4) {
            return Carbon::createFromFormat('Y', $value)->startOfYear()->format('Y-m-d');
        }

        // Try to parse as a regular date
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            // Create medical file
            $medicalFile = MedicalFile::create([
                'file_number' => $row['file_number'] ?? $row['رقم_الملف'],
                'region' => $row['region'] ?? $row['المنطقة'] ?? null,
                'diagnosis' => $row['diagnosis'] ?? $row['التشخيص'] ?? null,
                'registration_date' => $row['registration_date'] ?? $row['تاريخ_التسجيل'] ?? now(),
            ]);

            // Create husband patient
            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'husband',
                'name' => $row['husband_name'] ?? $row['الزوج_الاسم'],
                'national_id' => $row['husband_national_id'] ?? $row['الزوج_الرقم_الوطني'],
                'registry_number' => $row['husband_registry_number'] ?? $row['الزوج_رقم_القيد'],
                'dob' => $this->parseDate($row['husband_date_of_birth'] ?? $row['الزوج_تاريخ_الميلاد'] ?? null),
            ]);

            // Create wife patient
            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'wife',
                'name' => $row['wife_name'] ?? $row['الزوجة_الاسم'],
                'national_id' => $row['wife_national_id'] ?? $row['الزوجة_الرقم_الوطني'],
                'registry_number' => $row['wife_registry_number'] ?? $row['الزوجة_رقم_القيد'],
                'dob' => $this->parseDate($row['wife_date_of_birth'] ?? $row['الزوجة_تاريخ_الميلاد'] ?? null),
            ]);

            DB::commit();
            return $medicalFile;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'file_number' => 'required|unique:medical_files,file_number',
        ];
    }
}
