<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    protected $fillable = [
        'phone_numbers',
        'emails',
        'address',
        'company_name',
        'footer_text',
        'map_embed_url',
        'default_currency',
        'default_language',
        'status',
    ];

    public $translatable = [
        'address',
        'company_name',
        'footer_text',
    ];

    protected $casts = [
        'phone_numbers' => 'json',
        'emails' => 'json',
        'status' => 'boolean',
    ];
}
