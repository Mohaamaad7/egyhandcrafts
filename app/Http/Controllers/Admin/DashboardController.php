<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Craft;
use App\Models\CraftsmanStory;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dynamic admin command center dashboard.
     */
    public function index(): View
    {
        $craftsCount = Craft::count();
        $craftsWithCover = Craft::whereNotNull('cover_image')->count();

        $workshopsCount = Workshop::count();
        $activeWorkshopsCount = Workshop::where('is_active', true)->count();
        $totalWorkers = Workshop::sum('workers_count');

        $storiesCount = CraftsmanStory::count();
        $storiesWithVideo = CraftsmanStory::whereNotNull('youtube_url')->where('youtube_url', '!=', '')->count();
        $storiesWithAudio = CraftsmanStory::whereNotNull('audio_file')->where('audio_file', '!=', '')->count();

        $usersCount = User::count();

        $recentCrafts = Craft::latest()->take(5)->get();
        $recentWorkshops = Workshop::with('craft')->latest()->take(5)->get();
        $recentStories = CraftsmanStory::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'craftsCount',
            'craftsWithCover',
            'workshopsCount',
            'activeWorkshopsCount',
            'totalWorkers',
            'storiesCount',
            'storiesWithVideo',
            'storiesWithAudio',
            'usersCount',
            'recentCrafts',
            'recentWorkshops',
            'recentStories'
        ));
    }
}
