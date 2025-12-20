<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        'name',
        'job_title',
        'bio',
        'image',
        'email',
        'phone',
        'linkedin_url',
        'seo_meta',
        'order',
        'status',
    ];

    public $translatable = [
        'name',
        'job_title',
        'bio',
    ];

    protected $casts = [
        'seo_meta' => 'array',
        'order' => 'integer',
    ];
}
