<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Craft;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class WorkshopController extends Controller
{
    /**
     * Display a listing of the workshops.
     */
    public function index()
    {
        $workshops = Workshop::with('craft:id,title')
            ->latest()
            ->paginate(20);

        return view('admin.workshops.index', compact('workshops'));
    }

    /**
     * Show the form for creating a new workshop.
     */
    public function create()
    {
        $crafts = Craft::orderBy('title')->get(['id', 'title']);
        return view('admin.workshops.create', compact('crafts'));
    }

    /**
     * Store a newly created workshop in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'craft_id'          => 'nullable|exists:crafts,id',
            'craft_type'        => 'required|string|max:100',
            'location'          => 'required|string|max:255',
            'owner'             => 'required|string|max:255',
            'workers_count'     => 'required|string|max:50',
            'phone'             => 'required|string|max:100',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'short_description' => 'nullable|string',
            'content'           => 'nullable|string',
            'cover_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'         => 'nullable|boolean',
        ]);

        // Generate unique slug
        $validated['slug'] = Str::slug($validated['name'], '-');
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Workshop::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('workshops', 'public');
        }

        // Checkbox: default to true if not sent
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Workshop::create($validated);

        return redirect()->route('admin.workshops.index')
            ->with('success', 'تم إضافة الورشة بنجاح.');
    }

    /**
     * Show the form for editing the specified workshop.
     */
    public function edit(Workshop $workshop)
    {
        $crafts = Craft::orderBy('title')->get(['id', 'title']);
        return view('admin.workshops.edit', compact('workshop', 'crafts'));
    }

    /**
     * Update the specified workshop in storage.
     */
    public function update(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'craft_id'          => 'nullable|exists:crafts,id',
            'craft_type'        => 'required|string|max:100',
            'location'          => 'required|string|max:255',
            'owner'             => 'required|string|max:255',
            'workers_count'     => 'required|string|max:50',
            'phone'             => 'required|string|max:100',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'short_description' => 'nullable|string',
            'content'           => 'nullable|string',
            'cover_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'         => 'nullable|boolean',
        ]);

        // Regenerate slug if name changed
        $validated['slug'] = Str::slug($validated['name'], '-');
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Workshop::where('slug', $validated['slug'])->where('id', '!=', $workshop->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($workshop->cover_image) {
                Storage::disk('public')->delete($workshop->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')
                ->store('workshops', 'public');
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $workshop->update($validated);

        return redirect()->route('admin.workshops.index')
            ->with('success', 'تم تحديث بيانات الورشة بنجاح.');
    }

    /**
     * Remove the specified workshop from storage.
     */
    public function destroy(Workshop $workshop)
    {
        if ($workshop->cover_image) {
            Storage::disk('public')->delete($workshop->cover_image);
        }
        $workshop->delete();

        return redirect()->route('admin.workshops.index')
            ->with('success', 'تم حذف الورشة بنجاح.');
    }
}
