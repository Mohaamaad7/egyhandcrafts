<?php

namespace App\Http\Controllers;

use App\Models\Workshop;

class MapController extends Controller
{
    /**
     * Display the interactive heritage map with all active workshops.
     */
    public function index()
    {
        $workshops = Workshop::active()
            ->with('craft:id,title,slug')
            ->get([
                'id', 'name', 'slug', 'craft_id', 'craft_type',
                'location', 'owner', 'workers_count', 'phone',
                'latitude', 'longitude',
            ]);

        $labels = [
            'craft'              => __('الحرفة'),
            'allCrafts'          => __('كل الحرف'),
            'location'           => __('المكان'),
            'allLocations'       => __('كل الأماكن'),
            'craftLabel'         => __('الحرفة:'),
            'locationLabel'      => __('المكان:'),
            'ownerLabel'         => __('المالك:'),
            'workersLabel'       => __('عدد العمالة:'),
            'phoneLabel'         => __('الهاتف:'),
            'viewProfile'        => __('عرض ملف الورشة'),
            'heritageLayer'      => __('جوجل - نمط التراث (Heritage)'),
            'satelliteLayer'     => __('جوجل - قمر صناعي (Satellite)'),
        ];

        return view('map.index', [
            'workshopsJson' => $workshops->toJson(JSON_UNESCAPED_UNICODE),
            'labelsJson'    => json_encode($labels, JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * Display a single workshop's profile page.
     */
    public function show(string $slug)
    {
        $workshop = Workshop::where('slug', $slug)->firstOrFail();
        $workshop->load('craft');

        // Related workshops from the same craft
        $relatedWorkshops = Workshop::active()
            ->where('id', '!=', $workshop->id)
            ->where('craft_type', $workshop->craft_type)
            ->take(4)
            ->get();

        return view('workshops.show', compact('workshop', 'relatedWorkshops'));
    }
}
