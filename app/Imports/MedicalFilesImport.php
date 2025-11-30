<?php

namespace App\Imports;

use App\Models\MedicalFile;
use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class MedicalFilesImport implements ToModel, WithHeadingRow, WithValidation
{
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
                'dob' => $row['husband_date_of_birth'] ?? $row['الزوج_تاريخ_الميلاد'],
            ]);

            // Create wife patient
            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'wife',
                'name' => $row['wife_name'] ?? $row['الزوجة_الاسم'],
                'national_id' => $row['wife_national_id'] ?? $row['الزوجة_الرقم_الوطني'],
                'registry_number' => $row['wife_registry_number'] ?? $row['الزوجة_رقم_القيد'],
                'dob' => $row['wife_date_of_birth'] ?? $row['الزوجة_تاريخ_الميلاد'],
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
            'husband_name' => 'required',
            'husband_national_id' => 'required',
            'husband_registry_number' => 'required',
            'husband_date_of_birth' => 'required|date',
            'wife_name' => 'required',
            'wife_national_id' => 'required',
            'wife_registry_number' => 'required',
            'wife_date_of_birth' => 'required|date',
        ];
    }
}
