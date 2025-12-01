<?php

namespace App\Imports;

use App\Models\MedicalFile;
use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MedicalFilesImport implements ToCollection, WithChunkReading
{
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            // If it's a 4-digit year (1900-2100), treat as year only
            if ($value >= 1900 && $value <= 2100) {
                return $value . '-01-01';
            }
            // Otherwise treat as Excel serial date
            return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function collection(Collection $rows)
    {
        // Skip header row
        $rows = $rows->skip(1);

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                if (empty($row[0]) || strtolower($row[0]) == 'رقم الملف') {
                    continue;
                }

                $medicalFile = MedicalFile::updateOrCreate(
                    ['file_number' => $row[0]],
                    [
                        'region' => $row[8] ?? null,
                        'diagnosis' => $row[9] ?? null,
                        'registration_date' => now(),
                    ]
                );

                // Delete existing patients to avoid duplicates on re-import
                $medicalFile->patients()->delete();

                Patient::insert([
                    [
                        'medical_file_id' => $medicalFile->id,
                        'type' => 'husband',
                        'name' => $row[1] ?? null,
                        'national_id' => $row[3] ?? null,
                        'registry_number' => $row[5] ?? null,
                        'dob' => $this->parseDate($row[6] ?? null),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'medical_file_id' => $medicalFile->id,
                        'type' => 'wife',
                        'name' => $row[2] ?? null,
                        'national_id' => $row[4] ?? null,
                        'registry_number' => $row[5] ?? null,
                        'dob' => $this->parseDate($row[7] ?? null),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
