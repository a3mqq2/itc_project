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
                ['file_number' => $row[0]],
                [
                    'region' => $row[1] ?? null,
                    'diagnosis' => $row[2] ?? null,
                    'registration_date' => $this->parseDate($row[3] ?? null) ?? now(),
                ]
            );

            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'husband',
                'name' => $row[4] ?? null,
                'national_id' => $row[5] ?? null,
                'registry_number' => $row[6] ?? null,
                'dob' => $this->parseDate($row[7] ?? null),
            ]);

            Patient::create([
                'medical_file_id' => $medicalFile->id,
                'type' => 'wife',
                'name' => $row[8] ?? null,
                'national_id' => $row[9] ?? null,
                'registry_number' => $row[10] ?? null,
                'dob' => $this->parseDate($row[11] ?? null),
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
            '0' => 'nullable',
        ];
    }
}
