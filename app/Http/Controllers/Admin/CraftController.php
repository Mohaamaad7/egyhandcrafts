<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Craft;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CraftController extends Controller
{
    /**
     * Display a listing of the crafts.
     */
    public function index()
    {
        $crafts = Craft::latest()->paginate(15);
        return view('admin.crafts.index', compact('crafts'));
    }

    /**
     * Show the form for creating a new craft.
     */
    public function create()
    {
        return view('admin.crafts.create');
    }

    /**
     * Store a newly created craft in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'required|string',
            'content'           => 'required|string',
            'location'          => 'required|string|max:255',
            'cover_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['content'] = HtmlSanitizer::clean($validated['content']);
        $validated['slug'] = Str::slug($validated['title'], '-');

        // Ensure slug uniqueness
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Craft::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('crafts', 'public');
        }

        Craft::create($validated);

        return redirect()->route('admin.crafts.index')
            ->with('success', 'تم إضافة الحرفة بنجاح.');
    }

    /**
     * Show the form for editing the specified craft.
     */
    public function edit(Craft $craft)
    {
        return view('admin.crafts.edit', compact('craft'));
    }

    /**
     * Update the specified craft in storage.
     */
    public function update(Request $request, Craft $craft)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'required|string',
            'content'           => 'required|string',
            'location'          => 'required|string|max:255',
            'cover_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['content'] = HtmlSanitizer::clean($validated['content']);
        $validated['slug'] = Str::slug($validated['title'], '-');

        // Ensure slug uniqueness (excluding self)
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Craft::where('slug', $validated['slug'])->where('id', '!=', $craft->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($craft->cover_image) {
                Storage::disk('public')->delete($craft->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')
                ->store('crafts', 'public');
        }

        $craft->update($validated);

        return redirect()->route('admin.crafts.index')
            ->with('success', 'تم تحديث الحرفة بنجاح.');
    }

    /**
     * Remove the specified craft from storage.
     */
    public function destroy(Craft $craft)
    {
        if ($craft->cover_image) {
            Storage::disk('public')->delete($craft->cover_image);
        }
        $craft->delete();

        return redirect()->route('admin.crafts.index')
            ->with('success', 'تم حذف الحرفة بنجاح.');
    }

    /**
     * Upload an image from CKEditor 5 and return its public URL.
     */
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'upload' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('crafts/content', 'public');
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'url' => $url,
            ]);
        }

        return response()->json([
            'error' => [
                'message' => 'لم يتم استلام أي ملف صورة صالح للرفع.',
            ],
        ], 400);
    }
}
