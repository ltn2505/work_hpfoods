<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_activity_id',
        'original_name',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
    ];

    public function taskActivity()
    {
        return $this->belongsTo(TaskActivity::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 1) . ' ' . $units[$i];
    }

    public function isImage()
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/bmp',
            'image/svg+xml'
        ]);
    }

    public function getFileIcon()
    {
        if ($this->isImage()) {
            return 'bi bi-image';
        }
        
        $extension = strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'pdf':
                return 'bi bi-file-earmark-pdf';
            case 'doc':
            case 'docx':
                return 'bi bi-file-earmark-word';
            case 'xls':
            case 'xlsx':
                return 'bi bi-file-earmark-excel';
            case 'ppt':
            case 'pptx':
                return 'bi bi-file-earmark-ppt';
            case 'txt':
                return 'bi bi-file-earmark-text';
            case 'zip':
            case 'rar':
            case '7z':
                return 'bi bi-file-earmark-zip';
            default:
                return 'bi bi-file-earmark';
        }
    }
}
