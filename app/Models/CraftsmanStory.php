<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CraftsmanStory extends Model
{
    use HasFactory;

    protected $table = 'craftsmen_stories';

    protected $fillable = [
        'title',
        'slug',
        'craftsman_name',
        'craftsman_role',
        'photo',
        'content',
        'excerpt',
        'youtube_url',
        'audio_file',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // ─── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Scope to filter only published stories.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Get the resolved public URL for the craftsman's photo.
     * Falls back to a verified existing image asset.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
                return $this->photo;
            }

            if (Storage::disk('public')->exists($this->photo)) {
                return Storage::url($this->photo);
            }

            return asset('storage/' . ltrim($this->photo, '/'));
        }

        // Fallback: use card_stories.jpg if it exists, otherwise HeroBG.jpg
        $fallbackPath = public_path('assets/images/card_stories.jpg');
        if (file_exists($fallbackPath)) {
            return asset('assets/images/card_stories.jpg');
        }

        return asset('assets/images/HeroBG.jpg');
    }

    /**
     * Get the resolved public URL for the audio file, or null.
     */
    public function getAudioFileUrlAttribute(): ?string
    {
        if (!$this->audio_file) {
            return null;
        }

        if (filter_var($this->audio_file, FILTER_VALIDATE_URL)) {
            return $this->audio_file;
        }

        if (Storage::disk('public')->exists($this->audio_file)) {
            return Storage::url($this->audio_file);
        }

        return null;
    }

    /**
     * Extract YouTube video ID from various URL formats and return a
     * privacy-enhanced embed URL, or null if no valid video.
     *
     * Supported formats:
     *   - https://www.youtube.com/watch?v=XXXXXXXXXXX
     *   - https://youtu.be/XXXXXXXXXXX
     *   - https://youtube.com/shorts/XXXXXXXXXXX
     *   - URLs with extra params (?t=120, &list=..., etc.)
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->youtube_url) {
            return null;
        }

        $pattern = '/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{11})/';

        if (preg_match($pattern, $this->youtube_url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1];
        }

        return null;
    }

    /**
     * Check if this story has an audio recording that physically exists on disk.
     */
    public function getHasAudioAttribute(): bool
    {
        if (empty($this->audio_file)) {
            return false;
        }

        if (filter_var($this->audio_file, FILTER_VALIDATE_URL)) {
            return true;
        }

        return Storage::disk('public')->exists($this->audio_file);
    }

    /**
     * Check if this story has a valid video recording.
     */
    public function getHasVideoAttribute(): bool
    {
        return !empty($this->youtube_url) && !empty($this->youtube_embed_url);
    }

    /**
     * Get an excerpt of 20–25 words from the excerpt field or stripped content.
     */
    public function getExcerptTextAttribute(): string
    {
        $source = $this->excerpt ?: $this->content;
        $plain = strip_tags($source);
        $plain = html_entity_decode($plain, ENT_QUOTES, 'UTF-8');
        $plain = preg_replace('/\s+/u', ' ', trim($plain));
        $words = preg_split('/\s+/u', $plain, 26, PREG_SPLIT_NO_EMPTY);

        if (count($words) > 22) {
            return implode(' ', array_slice($words, 0, 22)) . '…';
        }

        return implode(' ', $words);
    }
}
