<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CraftsmanStory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CraftsmanStoryController extends Controller
{
    /**
     * Display a listing of all craftsmen stories.
     */
    public function index()
    {
        $stories = CraftsmanStory::latest()->paginate(15);
        return view('admin.stories.index', compact('stories'));
    }

    /**
     * Show the form for creating a new story.
     */
    public function create()
    {
        return view('admin.stories.create');
    }

    /**
     * Store a newly created story in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'craftsman_name' => 'required|string|max:255',
            'craftsman_role' => 'required|string|max:255',
            'content'        => 'required|string',
            'excerpt'        => 'nullable|string',
            'youtube_url'    => 'nullable|url|max:500',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'audio_file'     => 'nullable|file|mimes:mp3,wav,m4a,aac,ogg|max:51200',
            'is_published'   => 'nullable|boolean',
        ]);

        // Generate unique slug
        $validated['slug'] = Str::slug($validated['title'], '-');
        $originalSlug = $validated['slug'];
        $count = 1;
        while (CraftsmanStory::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        // Handle publication status (checkbox)
        $validated['is_published'] = $request->has('is_published');

        // Auto-generate excerpt from content if not provided
        if (empty($validated['excerpt'])) {
            $plain = strip_tags($validated['content']);
            $plain = html_entity_decode($plain, ENT_QUOTES, 'UTF-8');
            $plain = preg_replace('/\s+/u', ' ', trim($plain));
            $words = preg_split('/\s+/u', $plain, 30, PREG_SPLIT_NO_EMPTY);
            $validated['excerpt'] = implode(' ', array_slice($words, 0, 25)) . '…';
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('stories/photos', 'public');
        }

        // Handle audio file upload
        if ($request->hasFile('audio_file')) {
            $validated['audio_file'] = $request->file('audio_file')
                ->store('stories/audio', 'public');
        }

        CraftsmanStory::create($validated);

        return redirect()->route('admin.stories.index')
            ->with('success', 'تم إضافة قصة الحرفي بنجاح.');
    }

    /**
     * Show the form for editing the specified story.
     */
    public function edit(CraftsmanStory $story)
    {
        return view('admin.stories.edit', compact('story'));
    }

    /**
     * Update the specified story in storage.
     */
    public function update(Request $request, CraftsmanStory $story)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'craftsman_name' => 'required|string|max:255',
            'craftsman_role' => 'required|string|max:255',
            'content'        => 'required|string',
            'excerpt'        => 'nullable|string',
            'youtube_url'    => 'nullable|url|max:500',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'audio_file'     => 'nullable|file|mimes:mp3,wav,m4a,aac,ogg|max:51200',
            'delete_audio'   => 'nullable|boolean',
            'is_published'   => 'nullable|boolean',
        ]);

        // Generate unique slug (excluding self)
        $validated['slug'] = Str::slug($validated['title'], '-');
        $originalSlug = $validated['slug'];
        $count = 1;
        while (CraftsmanStory::where('slug', $validated['slug'])->where('id', '!=', $story->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        // Handle publication status (checkbox)
        $validated['is_published'] = $request->has('is_published');

        // Explicitly handle youtube_url so clearing the input saves as null
        $validated['youtube_url'] = $request->filled('youtube_url') ? $request->input('youtube_url') : null;

        // Auto-generate excerpt from content if not provided
        if (empty($validated['excerpt'])) {
            $plain = strip_tags($validated['content']);
            $plain = html_entity_decode($plain, ENT_QUOTES, 'UTF-8');
            $plain = preg_replace('/\s+/u', ' ', trim($plain));
            $words = preg_split('/\s+/u', $plain, 30, PREG_SPLIT_NO_EMPTY);
            $validated['excerpt'] = implode(' ', array_slice($words, 0, 25)) . '…';
        }

        // Handle photo upload (delete old if replaced, preserve if untouched)
        if ($request->hasFile('photo')) {
            if ($story->photo) {
                Storage::disk('public')->delete($story->photo);
            }
            $validated['photo'] = $request->file('photo')
                ->store('stories/photos', 'public');
        } else {
            unset($validated['photo']);
        }

        // Handle audio file deletion or replacement
        if ($request->boolean('delete_audio')) {
            if ($story->audio_file) {
                Storage::disk('public')->delete($story->audio_file);
            }
            $validated['audio_file'] = null;
        } elseif ($request->hasFile('audio_file')) {
            if ($story->audio_file) {
                Storage::disk('public')->delete($story->audio_file);
            }
            $validated['audio_file'] = $request->file('audio_file')
                ->store('stories/audio', 'public');
        } else {
            unset($validated['audio_file']);
        }

        unset($validated['delete_audio']);

        $story->update($validated);

        return redirect()->route('admin.stories.index')
            ->with('success', 'تم تحديث قصة الحرفي بنجاح.');
    }

    /**
     * Remove the specified story from storage.
     */
    public function destroy(CraftsmanStory $story)
    {
        if ($story->photo) {
            Storage::disk('public')->delete($story->photo);
        }
        if ($story->audio_file) {
            Storage::disk('public')->delete($story->audio_file);
        }

        $story->delete();

        return redirect()->route('admin.stories.index')
            ->with('success', 'تم حذف قصة الحرفي بنجاح.');
    }
}
