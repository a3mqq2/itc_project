<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'medical_file_id',
        'type',
        'name',
        'national_id',
        'registry_number',
        'dob'
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function medicalFile()
    {
        return $this->belongsTo(MedicalFile::class);
    }
}
