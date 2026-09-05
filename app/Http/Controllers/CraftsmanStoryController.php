<?php

namespace App\Http\Controllers;

use App\Models\CraftsmanStory;

class CraftsmanStoryController extends Controller
{
    /**
     * Display a paginated listing of published craftsmen stories.
     */
    public function index()
    {
        $stories = CraftsmanStory::published()
            ->latest()
            ->paginate(12);

        return view('stories.index', compact('stories'));
    }

    /**
     * Display the specified craftsmen story by slug.
     */
    public function show(string $slug)
    {
        $story = CraftsmanStory::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Get other published stories (exclude current)
        $otherStories = CraftsmanStory::published()
            ->where('id', '!=', $story->id)
            ->latest()
            ->limit(5)
            ->get();

        // Previous / Next navigation
        $prevStory = CraftsmanStory::published()
            ->where('id', '<', $story->id)
            ->orderByDesc('id')
            ->first();

        $nextStory = CraftsmanStory::published()
            ->where('id', '>', $story->id)
            ->orderBy('id')
            ->first();

        return view('stories.show', compact('story', 'otherStories', 'prevStory', 'nextStory'));
    }
}
