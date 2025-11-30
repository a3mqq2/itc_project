<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'medical_file_id',
        'include_husband',
        'include_wife',
        'notes'
    ];

    protected $casts = [
        'include_husband' => 'boolean',
        'include_wife' => 'boolean',
    ];

    public function medicalFile()
    {
        return $this->belongsTo(MedicalFile::class);
    }
}
