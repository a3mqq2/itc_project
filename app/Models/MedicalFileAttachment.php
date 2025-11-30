<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalFileAttachment extends Model
{
    protected $fillable = [
        'medical_file_id',
        'file_name',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'description',
    ];

    public function medicalFile(): BelongsTo
    {
        return $this->belongsTo(MedicalFile::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }

    public function getFileIconAttribute(): string
    {
        $extension = strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'ti-file-type-pdf',
            'doc', 'docx' => 'ti-file-type-doc',
            'xls', 'xlsx' => 'ti-file-type-xls',
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'ti-photo',
            'zip', 'rar' => 'ti-file-zip',
            default => 'ti-file',
        };
    }
}
