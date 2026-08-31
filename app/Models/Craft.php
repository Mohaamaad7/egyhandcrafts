<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Craft extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content',
        'location',
        'cover_image',
    ];

    /**
     * Get the resolved public URL for the craft's cover image.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            // If already a full URL
            if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
                return $this->cover_image;
            }

            // Check if file exists in public storage
            if (Storage::disk('public')->exists($this->cover_image)) {
                return Storage::url($this->cover_image);
            }

            // Direct public storage path
            return asset('storage/' . ltrim($this->cover_image, '/'));
        }

        // Elegant default fallback image
        return asset('assets/images/card_guide.jpg');
    }
}
