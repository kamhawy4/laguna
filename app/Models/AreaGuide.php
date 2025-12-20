<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AreaGuide extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'area_guides';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'seo_meta',
        'is_popular',
        'status',
        'sort_order',
    ];

    public $translatable = [
        'name',
        'slug',
        'description',
    ];

    protected $casts = [
        'seo_meta' => 'array',
        'is_popular' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the projects associated with this area guide.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'area_guide_project',
            'area_guide_id',
            'project_id'
        )->wherePivot('deleted_at', null)
            ->orderByPivot('sort_order')
            ->limit(5); // Max 5 projects per area guide
    }

    /**
     * Get published projects only.
     */
    public function publishedProjects(): BelongsToMany
    {
        return $this->projects()
            ->where('status', 'published');
    }
}
