<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalFile extends Model
{
    protected $fillable = [
        'file_number',
        'category_id',
        'region',
        'diagnosis',
        'registration_date'
    ];

    protected $casts = [
        'registration_date' => 'date',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function husband()
    {
        return $this->hasOne(Patient::class)->where('type', 'husband');
    }

    public function wife()
    {
        return $this->hasOne(Patient::class)->where('type', 'wife');
    }

    public function category()
    {
        return $this->belongsTo(MedicalFileCategory::class, 'category_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function attachments()
    {
        return $this->hasMany(MedicalFileAttachment::class);
    }
}
