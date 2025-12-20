<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        'client_name',
        'client_title',
        'testimonial',
        'rating',
        'client_image',
        'video_url',
        'is_featured',
        'order',
        'status',
    ];

    public $translatable = [
        'client_name',
        'client_title',
        'testimonial',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];
}
