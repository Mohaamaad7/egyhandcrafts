<?php

namespace App\Http\Controllers;

use App\Models\Craft;

class FrontendCraftController extends Controller
{
    /**
     * Display a paginated grid of all crafts.
     */
    public function index()
    {
        $crafts = Craft::latest()->paginate(9);
        return view('crafts.index', compact('crafts'));
    }

    /**
     * Display a single craft's full detail page.
     */
    public function show(string $slug)
    {
        $craft = Craft::where('slug', $slug)->firstOrFail();

        $relatedCrafts = Craft::where('id', '!=', $craft->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        $prevCraft = Craft::where('id', '<', $craft->id)->orderBy('id', 'desc')->first();
        $nextCraft = Craft::where('id', '>', $craft->id)->orderBy('id', 'asc')->first();

        return view('crafts.show', compact('craft', 'relatedCrafts', 'prevCraft', 'nextCraft'));
    }
}
