<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'core_values' => 'array',
        'competitor_analysis' => 'array',
        'main_content_formats' => 'array',
        'color_palette' => 'array',
        'monetization_strategy' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the assets for the project.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(ProjectAsset::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
