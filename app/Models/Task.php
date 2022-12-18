<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Task extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'calendar_id', 'task_id'];

    public function getDocumentsAttribute()
    {
        $documents = $this->getMedia();
        $documentOutput = [];

        foreach ($documents as $document) {
            $documentOutput []= $document->getFullUrl();
        }

        return $documentOutput;
    }
}
