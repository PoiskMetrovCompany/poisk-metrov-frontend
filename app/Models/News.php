<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
    ];

    protected $fillable = [
        'title',
        'author',
        'content',
        'title_image_file_name'
    ];

    public function deleteCurrentTitleImage()
    {
        if ($this->title_image_file_name != null &&
            Storage::disk('public_classic')->fileExists("/news/{$this->title_image_file_name}")) {
            Storage::disk('public_classic')->delete("/news/{$this->title_image_file_name}");
        }
    }
}
