<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'craft_id',
        'craft_type',
        'location',
        'owner',
        'workers_count',
        'phone',
        'latitude',
        'longitude',
        'short_description',
        'content',
        'cover_image',
        'is_active',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    /**
     * The craft (from the directory) this workshop belongs to.
     */
    public function craft(): BelongsTo
    {
        return $this->belongsTo(Craft::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────

    /**
     * Get the resolved public URL for the workshop's cover image.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
                return $this->cover_image;
            }

            if (Storage::disk('public')->exists($this->cover_image)) {
                return Storage::url($this->cover_image);
            }

            return asset('storage/' . ltrim($this->cover_image, '/'));
        }

        return asset('assets/images/card_map.jpg');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    /**
     * Scope to only active (visible) workshops.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mutator to sanitize rich HTML content before saving.
     */
    public function setContentAttribute($value): void
    {
        $this->attributes['content'] = $value !== null ? \App\Services\HtmlSanitizer::clean($value) : null;
    }
}
