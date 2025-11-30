<?php

namespace App\Imports;

use App\Models\MedicalFile;
use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MedicalFilesImport implements ToModel, WithHeadingRow, WithValidation
{
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
        }

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
            $medicalFile = MedicalFile::updateOrCreate(
                ['file_number' => $row['rkm_almlf']],
                [
                    'region' => $row['almntk'] ?? null,
                    'diagnosis' => $row['altshkhys'] ?? null,
                    'registration_date' => $this->parseDate($row['tarykh_altsgyl'] ?? null) ?? now(),
                ]
            );


            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'husband',
                'name' => $row['alzog_alasm'] ?? null,
                'national_id' => $row['alzog_alrkm_alotny'] ?? null,
                'registry_number' => $row['alzog_rkm_alkyd'] ?? null,
                'dob' => $this->parseDate($row['alzog_tarykh_almylad'] ?? null),
            ]);

            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'wife',
                'name' => $row['alzog_alasm_zawja'] ?? null,
                'national_id' => $row['alzog_alrkm_alotny_zawja'] ?? null,
                'registry_number' => $row['alzog_rkm_alkyd_zawja'] ?? null,
                'dob' => $this->parseDate($row['alzog_tarykh_almylad_zawja'] ?? null),
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
            'rkm_almlf' => 'nullable',
        ];
    }
}
