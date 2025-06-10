<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = ['title', 'text_content', 'file_path', 'file_hash'];

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getTextSummaryAttribute()
    {
        return Str::limit($this->text_content, 200);
    }

}
