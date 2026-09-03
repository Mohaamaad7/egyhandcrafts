@extends('layouts.app')

@section('title', $workshop->name . ' | ورش الحرف التراثية بمحافظة المنوفية')

@push('styles')
    {{-- Leaflet CSS for embedded mini-map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #workshopMiniMap {
            height: 280px;
            width: 100%;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
        }
        /* Heritage sepia on mini-map */
        .heritage-style .leaflet-tile-pane {
            filter: sepia(0.65) hue-rotate(-15deg) contrast(1.1) brightness(0.95);
        }
    </style>
@endpush

@section('content')
{{-- ============================== --}}
{{-- WORKSHOP DETAIL — HERO SECTION --}}
{{-- ============================== --}}
<section class="relative py-14 md:py-20 overflow-hidden text-white"
         style="background: linear-gradient(180deg, rgba(14, 27, 45, 0.88) 0%, rgba(26, 47, 76, 0.93) 55%, rgba(14, 27, 45, 0.98) 100%), url('{{ $workshop->cover_image_url }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    {{-- Ambient Glow Elements --}}
    <div class="absolute -top-20 -right-20 w-80 h-80 bg-accent/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-gold/15 rounded-full blur-3xl pointer-events-none"></div>

    {{-- Arabesque Pattern Watermark --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="arabesque-pattern w-full h-full"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10" data-aos="fade-up">

        {{-- Breadcrumb Navigation --}}
        <nav class="flex flex-wrap items-center gap-2 text-gray-300 text-sm mb-6 font-sans" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-accent transition-colors flex items-center gap-1.5">
                <i class="fas fa-home text-xs text-accent"></i>
                <span>الرئيسية</span>
            </a>
            <span class="text-gray-500">/</span>
            <a href="{{ route('map.index') }}" class="hover:text-accent transition-colors">
                <span>الخريطة التفاعلية</span>
            </a>
            <span class="text-gray-500">/</span>
            <span class="text-gold font-bold truncate max-w-xs">{{ $workshop->name }}</span>
        </nav>

        {{-- Title and Badges --}}
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2 bg-accent/20 text-accent border border-accent/40 px-3.5 py-1 rounded-full text-xs md:text-sm font-bold mb-4">
                <i class="fas fa-map-pin text-gold"></i>
                <span>ورشة حرفية موثقة</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-serif leading-tight mb-6">
                {{ $workshop->name }}
            </h1>

            <div class="flex flex-wrap items-center gap-3 text-sm">
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white border border-white/20 px-4 py-2 rounded-xl">
                    <i class="fas fa-briefcase text-accent"></i>
                    <span><strong>الحرفة:</strong> {{ $workshop->craft_type }}</span>
                </span>
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white border border-white/20 px-4 py-2 rounded-xl">
                    <i class="fas fa-map-marker-alt text-gold"></i>
                    <span><strong>المكان:</strong> {{ $workshop->location }}</span>
                </span>
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white border border-white/20 px-4 py-2 rounded-xl">
                    <i class="fas fa-user-tie text-accent"></i>
                    <span><strong>المالك:</strong> {{ $workshop->owner }}</span>
                </span>
            </div>
        </div>

    </div>
</section>

{{-- ============================== --}}
{{-- WORKSHOP DETAIL — MAIN CONTENT --}}
{{-- ============================== --}}
<section class="py-12 md:py-16 bg-background">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- MAIN COLUMN (8 of 12) --}}
            <main class="lg:col-span-8 flex flex-col gap-8 min-w-0">

                {{-- Cover Image Showcase --}}
                @if($workshop->cover_image)
                <div class="bg-white rounded-3xl overflow-hidden shadow-card-soft border border-gray-100/90 relative group" data-aos="fade-up">
                    <div class="relative max-h-[520px] overflow-hidden bg-primary/5">
                        <img src="{{ $workshop->cover_image_url }}"
                             alt="{{ $workshop->name }}"
                             class="w-full h-full max-h-[520px] object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/card_map.jpg') }}';">
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-camera text-accent"></i>
                            <span>صورة توثيقية ميدانية للورشة</span>
                        </span>
                        <span class="text-primary font-bold">{{ $workshop->location }}</span>
                    </div>
                </div>
                @endif

                {{-- Short Description (if present) --}}
                @if($workshop->short_description)
                <div class="bg-white border-r-4 border-accent rounded-3xl p-6 md:p-8 shadow-card-soft relative overflow-hidden" data-aos="fade-up">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center flex-shrink-0 text-xl">
                            <i class="fas fa-quote-right"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold font-serif text-primary mb-2">نبذة عن الورشة</h2>
                            <p class="text-gray-700 text-lg md:text-xl leading-loose font-sans">
                                {{ $workshop->short_description }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Rich Content Article (CKEditor HTML) --}}
                @if($workshop->content)
                <article class="bg-white rounded-3xl p-6 sm:p-8 md:p-12 shadow-card-soft border border-gray-100/80 min-w-0" data-aos="fade-up">
                    <div class="prose max-w-none text-gray-800 leading-relaxed font-sans min-w-0 w-full">
                        {!! $workshop->content !!}
                    </div>
                </article>
                @endif

                {{-- Embedded Mini Map --}}
                <div class="bg-white rounded-3xl p-6 shadow-card-soft border border-gray-100" data-aos="fade-up">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/15 text-accent flex items-center justify-center font-bold">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="text-lg font-bold font-serif text-primary">موقع الورشة على الخريطة</h3>
                    </div>
                    <div id="workshopMiniMap" class="heritage-style"></div>
                </div>

                {{-- Social Share & Action Toolbar --}}
                <div class="bg-white rounded-2xl p-6 shadow-card-soft border border-gray-100 flex flex-wrap items-center justify-between gap-4" data-aos="fade-up">
                    <div class="flex items-center gap-2 text-primary font-bold">
                        <i class="fas fa-share-alt text-accent text-lg"></i>
                        <span>مشاركة هذه الورشة:</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($workshop->name . ' - ' . url()->current()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 bg-[#25D366] text-white px-3.5 py-2 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                            <i class="fab fa-whatsapp text-sm"></i>
                            <span>واتساب</span>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 bg-[#1877F2] text-white px-3.5 py-2 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                            <i class="fab fa-facebook-f text-sm"></i>
                            <span>فيسبوك</span>
                        </a>
                        <button type="button" onclick="copyWorkshopUrl()"
                                class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 px-3.5 py-2 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors">
                            <i class="fas fa-link text-sm"></i>
                            <span id="copyBtnText">نسخ الرابط</span>
                        </button>
                    </div>
                </div>

            </main>

            {{-- SIDEBAR COLUMN (4 of 12) --}}
            <aside class="lg:col-span-4 flex flex-col gap-6 lg:sticky lg:top-24">

                {{-- Workshop Identity Card --}}
                <div class="bg-white rounded-3xl p-6 shadow-card-soft border border-gray-100" data-aos="fade-up">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-accent/15 text-accent flex items-center justify-center font-bold">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold font-serif text-primary">بطاقة بيانات الورشة</h3>
                            <span class="text-xs text-gray-400">البيانات الميدانية الرسمية</span>
                        </div>
                    </div>

                    <ul class="flex flex-col gap-4 text-sm font-sans">
                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-store text-accent/80 text-xs"></i>
                                <span>اسم الورشة</span>
                            </span>
                            <span class="font-bold text-primary text-right max-w-[60%]">{{ $workshop->name }}</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-briefcase text-accent/80 text-xs"></i>
                                <span>الحرفة</span>
                            </span>
                            <span class="font-bold text-primary">{{ $workshop->craft_type }}</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-accent/80 text-xs"></i>
                                <span>المكان</span>
                            </span>
                            <span class="font-bold text-primary">{{ $workshop->location }}</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-user-tie text-accent/80 text-xs"></i>
                                <span>المالك</span>
                            </span>
                            <span class="font-bold text-primary text-right">{{ $workshop->owner }}</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-users text-accent/80 text-xs"></i>
                                <span>عدد العمالة</span>
                            </span>
                            <span class="font-bold text-primary">{{ $workshop->workers_count }}</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-phone text-accent/80 text-xs"></i>
                                <span>الهاتف</span>
                            </span>
                            <span class="font-bold text-primary" dir="ltr">{{ $workshop->phone }}</span>
                        </li>

                        <li class="flex items-start justify-between">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-shield-alt text-accent/80 text-xs"></i>
                                <span>الحالة</span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 font-bold px-2.5 py-0.5 rounded-full border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>ورشة موثقة نشطة</span>
                            </span>
                        </li>
                    </ul>

                    {{-- View on Map CTA --}}
                    <div class="mt-6 pt-5 border-t border-gray-100">
                        <a href="{{ route('map.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 px-4 rounded-xl hover:bg-accent transition-colors duration-300 shadow-sm text-sm">
                            <i class="fas fa-map-marked-alt text-xs"></i>
                            <span>عرض على الخريطة التفاعلية</span>
                        </a>
                    </div>

                    @if($workshop->craft)
                    <div class="mt-3">
                        <a href="{{ route('crafts.show', $workshop->craft->slug) }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-white text-primary font-bold py-3 px-4 rounded-xl border border-gray-200 hover:border-accent hover:text-accent transition-colors duration-300 text-sm">
                            <i class="fas fa-book-open text-xs"></i>
                            <span>قراءة عن حرفة {{ $workshop->craft->title }}</span>
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Related Workshops --}}
                @if($relatedWorkshops->count() > 0)
                <div class="bg-white rounded-3xl p-6 shadow-card-soft border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                        <h3 class="text-lg font-bold font-serif text-primary">ورش مشابهة</h3>
                        <a href="{{ route('map.index') }}" class="text-xs text-accent hover:underline font-bold">عرض الكل</a>
                    </div>

                    <div class="flex flex-col gap-4">
                        @foreach($relatedWorkshops as $related)
                        <a href="{{ route('workshops.show', $related->slug) }}"
                           class="flex items-center gap-3 p-2 rounded-2xl hover:bg-gray-50 transition-colors group">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0 text-accent">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="overflow-hidden flex-grow">
                                <h4 class="text-sm font-bold font-serif text-primary truncate group-hover:text-accent transition-colors">
                                    {{ $related->name }}
                                </h4>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                    <i class="fas fa-user text-accent text-[10px]"></i>
                                    <span class="truncate">{{ $related->owner }}</span>
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Project Info Card --}}
                <div class="bg-gradient-to-br from-primary to-primary-dark text-white rounded-3xl p-6 shadow-card-soft relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute inset-0 opacity-10 arabesque-pattern"></div>
                    <div class="relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-gold/20 text-gold flex items-center justify-center mb-4">
                            <i class="fas fa-landmark text-lg"></i>
                        </div>
                        <h4 class="text-base font-bold font-serif text-gold mb-2">مشروع توثيق الحرف التراثية</h4>
                        <p class="text-xs text-gray-300 leading-relaxed mb-4">
                            مبادرة بحثية وميدانية تقودها كلية السياحة والفنادق بجامعة مدينة السادات للحفاظ على التراث اللامادي والصناعات اليدوية بمحافظة المنوفية.
                        </p>
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-accent hover:text-white transition-colors">
                            <span>العودة إلى البوابة الرئيسية</span>
                            <i class="fas fa-arrow-left text-[10px]"></i>
                        </a>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</section>

{{-- Client-side scripts --}}
<script>
function copyWorkshopUrl() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        var btnText = document.getElementById('copyBtnText');
        if (btnText) {
            var original = btnText.innerText;
            btnText.innerText = 'تم النسخ!';
            setTimeout(function() { btnText.innerText = original; }, 2000);
        }
    });
}
</script>
@endsection

@push('scripts')
    {{-- Leaflet JS for embedded mini-map --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var lat = {{ $workshop->latitude }};
            var lng = {{ $workshop->longitude }};

            var map = L.map('workshopMiniMap', {
                scrollWheelZoom: false,
                dragging: true,
                zoomControl: true
            }).setView([lat, lng], 16);

            L.tileLayer('https://mt1.google.com/vt/lyrs=m&hl=ar&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                attribution: '© Google Maps'
            }).addTo(map);

            var iconHtml = '<div class="custom-marker" style="background-color:#006064;display:flex;justify-content:center;align-items:center;width:36px;height:36px;border-radius:50%;color:white;box-shadow:0 3px 6px rgba(0,0,0,0.4);border:2px solid #fffaf0;font-size:16px;"><i class="fa-solid fa-map-pin"></i></div>';

            var customIcon = L.divIcon({
                className: 'custom-icon-wrapper',
                html: iconHtml,
                iconSize: [36, 36],
                iconAnchor: [18, 18]
            });

            L.marker([lat, lng], { icon: customIcon }).addTo(map);
        });
    </script>
@endpush
