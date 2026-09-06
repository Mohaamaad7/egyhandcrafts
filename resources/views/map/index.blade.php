@extends('layouts.app')

@section('title', 'الخريطة التفاعلية | ورش الحرف التراثية بمحافظة المنوفية')

@push('styles')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

        /* ─── Map Container ───────────────────────────── */
        #heritageMap {
            width: 100%;
            height: calc(100vh - 220px);
            min-height: 500px;
            z-index: 1;
        }

        /* Heritage sepia filter on map tiles */
        .heritage-style .leaflet-tile-pane {
            filter: sepia(0.65) hue-rotate(-15deg) contrast(1.1) brightness(0.95);
        }

        /* ─── Floating Filter Panel ───────────────────── */
        .filter-panel {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 248, 236, 0.95);
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border: 1px solid #c8a97e;
            z-index: 1000;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .filter-panel h3 {
            margin: 0 0 0 15px;
            color: #5a3a1f;
            font-size: 18px;
            font-family: 'Amiri', serif;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .filter-group label {
            font-size: 13px;
            color: #6a4a2f;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .filter-group select {
            padding: 8px;
            border: 1px solid #c8a97e;
            border-radius: 4px;
            min-width: 150px;
            font-family: inherit;
            outline: none;
            background-color: #fffaf0;
            color: #333;
        }

        /* ─── Popup Styles ────────────────────────────── */
        .leaflet-popup-content-wrapper {
            direction: rtl;
            text-align: right;
            border-radius: 8px;
            background: #fffaf0;
            border: 1px solid #c8a97e;
        }
        .popup-container {
            min-width: 240px;
        }
        .popup-container h4 {
            color: #5a3a1f;
            margin: 0 0 10px 0;
            border-bottom: 2px solid #eaddc5;
            padding-bottom: 8px;
            font-size: 18px;
            font-family: 'Amiri', serif;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .popup-container p {
            margin: 6px 0;
            font-size: 14px;
            color: #4a3a2a;
        }
        .popup-container i {
            color: #a47c43;
            width: 18px;
            text-align: center;
        }
        .phone-number {
            direction: ltr;
            display: inline-block;
            color: #8a2a2a;
            font-weight: bold;
            font-family: Tahoma, sans-serif;
        }
        .popup-cta {
            display: block;
            margin-top: 12px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #1A2F4C 0%, #264268 100%);
            color: #fff;
            text-align: center;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }
        .popup-cta:hover {
            background: linear-gradient(135deg, #E67E22 0%, #D4AF37 100%);
            color: #fff;
        }
        .popup-cta i {
            color: #D4AF37;
            margin-left: 6px;
        }

        /* ─── Tooltip Style ───────────────────────────── */
        .custom-tooltip {
            background: rgba(255, 248, 236, 0.95);
            border: 1px solid #c8a97e;
            border-radius: 4px;
            font-weight: bold;
            color: #5a3a1f;
            direction: rtl;
            text-align: right;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-family: 'Cairo', sans-serif;
        }

        /* ─── Custom Marker Styles ────────────────────── */
        .custom-marker {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: white;
            box-shadow: 0 3px 6px rgba(0,0,0,0.4);
            border: 2px solid #fffaf0;
            font-size: 16px;
        }
        .marker-sadaf  { background-color: #006064; } /* Deep Teal */
        .marker-sirma  { background-color: #b8860b; color: #fff; } /* Golden */
        .marker-default { background-color: #8d6e63; } /* Brown/Vintage */

        /* ─── Layer Control RTL ───────────────────────── */
        .leaflet-control-layers {
            direction: rtl;
            text-align: right;
            font-family: 'Cairo', sans-serif;
            border-radius: 8px;
            padding: 5px;
            background: rgba(255, 248, 236, 0.95) !important;
            border: 1px solid #c8a97e !important;
        }

        /* ─── Map Section Hero Header ─────────────────── */
        .map-hero-bar {
            background: linear-gradient(135deg, #1A2F4C 0%, #264268 100%);
            color: #fff;
            padding: 18px 0;
            border-bottom: 3px solid #D4AF37;
        }

        /* ─── Responsive Filter Panel ─────────────────── */
        @media (max-width: 640px) {
            .filter-panel {
                flex-direction: column;
                right: 10px;
                left: 10px;
                top: 10px;
                gap: 8px;
                padding: 12px 15px;
            }
            .filter-panel h3 {
                font-size: 15px;
                margin: 0;
            }
            .filter-group select {
                min-width: 100%;
            }
            #heritageMap {
                height: calc(100vh - 260px);
                min-height: 400px;
            }
        }
    </style>
@endpush

@section('content')

{{-- ─── Map Hero Bar ──────────────────────────────────── --}}
<div class="map-hero-bar">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gold/20 text-gold flex items-center justify-center">
                    <i class="fas fa-map-marked-alt text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold font-serif">الخريطة التفاعلية لورش الحرف التراثية</h1>
                    <p class="text-gray-300 text-xs md:text-sm">التوزيع الجغرافي لورش الحرف اليدوية الموثقة بمحافظة المنوفية</p>
                </div>
            </div>
            <nav class="flex flex-wrap items-center gap-2 text-gray-300 text-sm" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-gold transition-colors flex items-center gap-1.5">
                    <i class="fas fa-home text-xs text-gold"></i>
                    <span>الرئيسية</span>
                </a>
                <span class="text-gray-500">/</span>
                <span class="text-gold font-bold">الخريطة التفاعلية</span>
            </nav>
        </div>
    </div>
</div>

{{-- ─── Map Container ─────────────────────────────────── --}}
<div class="relative" style="background-color: #eaddc5;">

    {{-- Floating Filter Panel --}}
    <div class="filter-panel" id="filterPanel">
        <h3><i class="fa-solid fa-scroll"></i> خريطة الحرف التراثية</h3>
        <div class="filter-group">
            <label for="craftFilter" id="labelCraft"></label>
            <select id="craftFilter">
                <option value="all" id="optAllCrafts"></option>
            </select>
        </div>
        <div class="filter-group">
            <label for="locFilter" id="labelLocation"></label>
            <select id="locFilter">
                <option value="all" id="optAllLocations"></option>
            </select>
        </div>
    </div>

    {{-- Map div with heritage style --}}
    <div id="heritageMap" class="heritage-style"></div>
</div>

@endsection

@push('scripts')
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function () {
        'use strict';

        // ── HTML Escaping Utility for XSS Prevention ────────────
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // ── Localized UI labels (future i18n ready) ──────────────
        var labels = @json($labels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);

        // Apply labels to filter panel
        document.getElementById('labelCraft').textContent = labels.craft;
        document.getElementById('optAllCrafts').textContent = labels.allCrafts;
        document.getElementById('labelLocation').textContent = labels.location;
        document.getElementById('optAllLocations').textContent = labels.allLocations;

        // ── Workshop data (from database via secure Blade directive) ───────
        var workshops = @json($workshops, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);

        // ── Base URL for workshop profiles ───────────────────────
        var workshopBaseUrl = @json(url('/workshops'));

        // ── Google Maps Tile Layers ──────────────────────────────
        var googleStreets = L.tileLayer('https://mt1.google.com/vt/lyrs=m&hl=ar&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            attribution: '© Google Maps'
        });

        var googleSatellite = L.tileLayer('https://mt1.google.com/vt/lyrs=y&hl=ar&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            attribution: '© Google Maps'
        });

        // ── Setup Map ────────────────────────────────────────────
        var map = L.map('heritageMap', {
            zoomControl: false,
            layers: [googleStreets]
        }).setView([30.382367755555556, 30.89268463888889], 13);

        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        var baseMaps = {};
        baseMaps[labels.heritageLayer] = googleStreets;
        baseMaps[labels.satelliteLayer] = googleSatellite;
        L.control.layers(baseMaps, null, { position: 'topleft' }).addTo(map);

        // Toggle heritage CSS filter based on selected layer
        map.on('baselayerchange', function (e) {
            var mapEl = document.getElementById('heritageMap');
            if (e.name === labels.heritageLayer) {
                mapEl.classList.add('heritage-style');
            } else {
                mapEl.classList.remove('heritage-style');
            }
        });

        var markersLayer = L.layerGroup().addTo(map);

        // ── Populate Filter Dropdowns ────────────────────────────
        var crafts   = [...new Set(workshops.map(function(w) { return w.craft_type; }))];
        var locations = [...new Set(workshops.map(function(w) { return w.location; }))];

        var craftSelect = document.getElementById('craftFilter');
        crafts.forEach(function (c) {
            var opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            craftSelect.appendChild(opt);
        });

        var locSelect = document.getElementById('locFilter');
        locations.forEach(function (l) {
            var opt = document.createElement('option');
            opt.value = l;
            opt.textContent = l;
            locSelect.appendChild(opt);
        });

        // ── Icon Configuration ───────────────────────────────────
        function getIconConfig(craftType) {
            if (craftType.indexOf('الصدف') !== -1) {
                return { cls: 'marker-sadaf', icon: 'fa-gem' };
            } else if (craftType.indexOf('السيرما') !== -1) {
                return { cls: 'marker-sirma', icon: 'fa-scroll' };
            } else {
                return { cls: 'marker-default', icon: 'fa-hammer' };
            }
        }

        // ── Render Markers ───────────────────────────────────────
        function renderMarkers() {
            markersLayer.clearLayers();
            var selectedCraft = craftSelect.value;
            var selectedLoc   = locSelect.value;

            var filteredData = workshops.filter(function (w) {
                return (selectedCraft === 'all' || w.craft_type === selectedCraft) &&
                       (selectedLoc   === 'all' || w.location   === selectedLoc);
            });

            filteredData.forEach(function (ws) {
                var iconConfig = getIconConfig(ws.craft_type);

                var customIcon = L.divIcon({
                    className: 'custom-icon-wrapper',
                    html: '<div class="custom-marker ' + iconConfig.cls + '"><i class="fa-solid ' + iconConfig.icon + '"></i></div>',
                    iconSize: [36, 36],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -18]
                });

                var popupContent =
                    '<div class="popup-container">' +
                        '<h4><i class="fa-solid ' + iconConfig.icon + '"></i> ' + escapeHtml(ws.name) + '</h4>' +
                        '<p><i class="fa-solid fa-briefcase"></i> <b>' + escapeHtml(labels.craftLabel) + '</b> ' + escapeHtml(ws.craft_type) + '</p>' +
                        '<p><i class="fa-solid fa-map-location-dot"></i> <b>' + escapeHtml(labels.locationLabel) + '</b> ' + escapeHtml(ws.location) + '</p>' +
                        '<p><i class="fa-solid fa-user-tie"></i> <b>' + escapeHtml(labels.ownerLabel) + '</b> ' + escapeHtml(ws.owner) + '</p>' +
                        '<p><i class="fa-solid fa-users"></i> <b>' + escapeHtml(labels.workersLabel) + '</b> ' + escapeHtml(ws.workers_count) + '</p>' +
                        '<p><i class="fa-solid fa-phone"></i> <b>' + escapeHtml(labels.phoneLabel) + '</b> <span class="phone-number">' + escapeHtml(ws.phone) + '</span></p>' +
                        '<a href="' + workshopBaseUrl + '/' + encodeURIComponent(ws.slug) + '" class="popup-cta">' +
                            '<i class="fa-solid fa-door-open"></i>' + escapeHtml(labels.viewProfile) +
                        '</a>' +
                    '</div>';

                L.marker([ws.latitude, ws.longitude], { icon: customIcon })
                    .bindPopup(popupContent)
                    .bindTooltip(escapeHtml(ws.location) + ' - ' + escapeHtml(ws.name), {
                        direction: 'top',
                        offset: [0, -15],
                        className: 'custom-tooltip'
                    })
                    .addTo(markersLayer);
            });
        }

        craftSelect.addEventListener('change', renderMarkers);
        locSelect.addEventListener('change', renderMarkers);

        renderMarkers();
    })();
    </script>
@endpush
