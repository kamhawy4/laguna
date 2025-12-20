<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class SocialMediaLink extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'platform',
        'label',
        'url',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public $translatable = ['label'];

    /**
     * Get the platforms enum values.
     */
    public static function getPlatforms(): array
    {
        return ['facebook', 'instagram', 'linkedin', 'twitter', 'youtube', 'tiktok'];
    }

    /**
     * Get platforms with their default icons.
     */
    public static function getPlatformsWithIcons(): array
    {
        return [
            'facebook' => 'fab fa-facebook',
            'instagram' => 'fab fa-instagram',
            'linkedin' => 'fab fa-linkedin',
            'twitter' => 'fab fa-twitter',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
        ];
    }
}
