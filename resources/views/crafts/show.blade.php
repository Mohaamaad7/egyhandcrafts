@extends('layouts.app')

@section('title', $craft->title . ' | دليل الحرف التراثية بمحافظة المنوفية')

@section('content')
{{-- ============================== --}}
{{-- CRAFT DETAIL — HERO SECTION     --}}
{{-- ============================== --}}
<section class="relative py-14 md:py-20 overflow-hidden text-white"
         style="background: linear-gradient(180deg, rgba(14, 27, 45, 0.88) 0%, rgba(26, 47, 76, 0.93) 55%, rgba(14, 27, 45, 0.98) 100%), url('{{ $craft->cover_image_url }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

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
            <a href="{{ route('crafts.index') }}" class="hover:text-accent transition-colors">
                <span>دليل الحرف التراثية</span>
            </a>
            <span class="text-gray-500">/</span>
            <span class="text-gold font-bold truncate max-w-xs">{{ $craft->title }}</span>
        </nav>

        {{-- Title and Badges --}}
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2 bg-accent/20 text-accent border border-accent/40 px-3.5 py-1 rounded-full text-xs md:text-sm font-bold mb-4">
                <i class="fas fa-certificate text-gold"></i>
                <span>توثيق الحرف التراثية الأصيلة</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-serif leading-tight mb-6">
                {{ $craft->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-3 text-sm">
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white border border-white/20 px-4 py-2 rounded-xl">
                    <i class="fas fa-map-marker-alt text-accent"></i>
                    <span><strong>الموقع:</strong> {{ $craft->location }}</span>
                </span>
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white border border-white/20 px-4 py-2 rounded-xl">
                    <i class="fas fa-university text-gold"></i>
                    <span>جامعة مدينة السادات</span>
                </span>
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-gray-300 border border-white/10 px-4 py-2 rounded-xl">
                    <i class="fas fa-calendar-check text-accent/80"></i>
                    <span>سنة التوثيق: {{ $craft->created_at ? $craft->created_at->format('Y') : date('Y') }}</span>
                </span>
            </div>
        </div>

    </div>
</section>

{{-- ============================== --}}
{{-- CRAFT DETAIL — MAIN CONTENT    --}}
{{-- ============================== --}}
<section class="py-12 md:py-16 bg-background">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- MAIN COLUMN (8 of 12) --}}
            <main class="lg:col-span-8 flex flex-col gap-8">

                {{-- Cover Image Showcase --}}
                <div class="bg-white rounded-3xl overflow-hidden shadow-card-soft border border-gray-100/90 relative group" data-aos="fade-up">
                    <div class="relative max-h-[520px] overflow-hidden bg-primary/5">
                        <img src="{{ $craft->cover_image_url }}"
                             alt="{{ $craft->title }}"
                             id="craftCoverImg"
                             class="w-full h-full max-h-[520px] object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/card_guide.jpg') }}';">
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-camera text-accent"></i>
                            <span>صورة توثيقية ميدانية للحرفة</span>
                        </span>
                        <span class="text-primary font-bold">{{ $craft->location }}</span>
                    </div>
                </div>

                {{-- Executive Summary Box (Short Description) --}}
                <div class="bg-white border-r-4 border-accent rounded-3xl p-6 md:p-8 shadow-card-soft relative overflow-hidden" data-aos="fade-up">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center flex-shrink-0 text-xl">
                            <i class="fas fa-quote-right"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold font-serif text-primary mb-2">نبذة تمهيدية عن الحرفة</h2>
                            <p class="text-gray-700 text-lg md:text-xl leading-loose font-sans">
                                {{ $craft->short_description }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rich Content Article (CKEditor HTML) --}}
                <article class="bg-white rounded-3xl p-6 sm:p-8 md:p-12 shadow-card-soft border border-gray-100/80" data-aos="fade-up">
                    <div class="prose max-w-none text-gray-800 leading-relaxed font-sans">
                        {!! $craft->content !!}
                    </div>
                </article>

                {{-- Social Share & Action Toolbar --}}
                <div class="bg-white rounded-2xl p-6 shadow-card-soft border border-gray-100 flex flex-wrap items-center justify-between gap-4" data-aos="fade-up">
                    <div class="flex items-center gap-2 text-primary font-bold">
                        <i class="fas fa-share-alt text-accent text-lg"></i>
                        <span>مشاركة هذه الحرفة:</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        {{-- WhatsApp --}}
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($craft->title . ' - ' . url()->current()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 bg-[#25D366] text-white px-3.5 py-2 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                            <i class="fab fa-whatsapp text-sm"></i>
                            <span>واتساب</span>
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 bg-[#1877F2] text-white px-3.5 py-2 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                            <i class="fab fa-facebook-f text-sm"></i>
                            <span>فيسبوك</span>
                        </a>

                        {{-- X (Twitter) --}}
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($craft->title) }}&url={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 bg-black text-white px-3.5 py-2 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                            <i class="fab fa-x-twitter text-sm"></i>
                            <span>إكس</span>
                        </a>

                        {{-- Copy Link --}}
                        <button type="button" onclick="copyCurrentUrl()"
                                class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 px-3.5 py-2 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors">
                            <i class="fas fa-link text-sm"></i>
                            <span id="copyBtnText">نسخ الرابط</span>
                        </button>

                        {{-- Print --}}
                        <button type="button" onclick="window.print()"
                                class="inline-flex items-center gap-1.5 bg-primary/10 text-primary px-3.5 py-2 rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-print text-sm"></i>
                            <span>طباعة</span>
                        </button>
                    </div>
                </div>

                {{-- Previous / Next Craft Navigation --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2" data-aos="fade-up">
                    @if(isset($prevCraft) && $prevCraft->id !== $craft->id)
                        <a href="{{ route('crafts.show', $prevCraft->slug) }}"
                           class="bg-white p-5 rounded-2xl border border-gray-100 shadow-card-soft hover:shadow-card-hover hover:border-accent/40 transition-all flex items-center gap-4 group">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 group-hover:bg-accent group-hover:text-white transition-colors">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="overflow-hidden">
                                <span class="text-xs text-gray-400 block mb-0.5">الحرفة السابقة</span>
                                <h4 class="text-sm font-bold font-serif text-primary truncate group-hover:text-accent transition-colors">
                                    {{ $prevCraft->title }}
                                </h4>
                            </div>
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if(isset($nextCraft) && $nextCraft->id !== $craft->id)
                        <a href="{{ route('crafts.show', $nextCraft->slug) }}"
                           class="bg-white p-5 rounded-2xl border border-gray-100 shadow-card-soft hover:shadow-card-hover hover:border-accent/40 transition-all flex items-center justify-between gap-4 text-left group">
                            <div class="overflow-hidden text-right flex-grow">
                                <span class="text-xs text-gray-400 block mb-0.5">الحرفة التالية</span>
                                <h4 class="text-sm font-bold font-serif text-primary truncate group-hover:text-accent transition-colors">
                                    {{ $nextCraft->title }}
                                </h4>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 group-hover:bg-accent group-hover:text-white transition-colors">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    @endif
                </div>

            </main>

            {{-- SIDEBAR COLUMN (4 of 12) --}}
            <aside class="lg:col-span-4 flex flex-col gap-6 lg:sticky lg:top-24">

                {{-- Craft Identity Card --}}
                <div class="bg-white rounded-3xl p-6 shadow-card-soft border border-gray-100" data-aos="fade-up">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-accent/15 text-accent flex items-center justify-center font-bold">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold font-serif text-primary">بطاقة توثيق الحرفة</h3>
                            <span class="text-xs text-gray-400">البيانات الميدانية الرسمية</span>
                        </div>
                    </div>

                    <ul class="flex flex-col gap-4 text-sm font-sans">
                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-tag text-accent/80 text-xs"></i>
                                <span>اسم الحرفة</span>
                            </span>
                            <span class="font-bold text-primary text-right">{{ $craft->title }}</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-accent/80 text-xs"></i>
                                <span>الموقع / المركز</span>
                            </span>
                            <span class="font-bold text-primary text-right">{{ $craft->location }}</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-map text-accent/80 text-xs"></i>
                                <span>المحافظة</span>
                            </span>
                            <span class="font-bold text-primary">محافظة المنوفية</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-accent/80 text-xs"></i>
                                <span>سنة التوثيق</span>
                            </span>
                            <span class="font-bold text-primary">{{ $craft->created_at ? $craft->created_at->format('Y') : date('Y') }} م</span>
                        </li>

                        <li class="flex items-start justify-between pb-3 border-b border-gray-50">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-university text-accent/80 text-xs"></i>
                                <span>الجهة الموثقة</span>
                            </span>
                            <span class="font-bold text-primary text-right text-xs">كلية السياحة والفنادق</span>
                        </li>

                        <li class="flex items-start justify-between">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fas fa-shield-alt text-accent/80 text-xs"></i>
                                <span>الحالة التراثية</span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 font-bold px-2.5 py-0.5 rounded-full border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>تراث حي موثق</span>
                            </span>
                        </li>
                    </ul>

                    <div class="mt-6 pt-5 border-t border-gray-100">
                        <a href="{{ route('crafts.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 px-4 rounded-xl hover:bg-accent transition-colors duration-300 shadow-sm text-sm">
                            <i class="fas fa-th-large text-xs"></i>
                            <span>تصفح كافة الحرف بالدليل</span>
                        </a>
                    </div>
                </div>

                {{-- Other Crafts Section --}}
                @if(isset($relatedCrafts) && $relatedCrafts->count() > 0)
                <div class="bg-white rounded-3xl p-6 shadow-card-soft border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                        <h3 class="text-lg font-bold font-serif text-primary">حرف تراثية أخرى</h3>
                        <a href="{{ route('crafts.index') }}" class="text-xs text-accent hover:underline font-bold">عرض الكل</a>
                    </div>

                    <div class="flex flex-col gap-4">
                        @foreach($relatedCrafts as $related)
                        <a href="{{ route('crafts.show', $related->slug) }}"
                           class="flex items-center gap-3 p-2 rounded-2xl hover:bg-gray-50 transition-colors group">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-primary/10 flex-shrink-0 relative">
                                <img src="{{ $related->cover_image_url }}"
                                     alt="{{ $related->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/card_guide.jpg') }}';">
                            </div>
                            <div class="overflow-hidden flex-grow">
                                <h4 class="text-sm font-bold font-serif text-primary truncate group-hover:text-accent transition-colors">
                                    {{ $related->title }}
                                </h4>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                    <i class="fas fa-map-marker-alt text-accent text-[10px]"></i>
                                    <span class="truncate">{{ $related->location }}</span>
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

{{-- Client-side Copy URL Script --}}
<script>
function copyCurrentUrl() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        const btnText = document.getElementById('copyBtnText');
        if (btnText) {
            const original = btnText.innerText;
            btnText.innerText = 'تم النسخ!';
            setTimeout(() => {
                btnText.innerText = original;
            }, 2000);
        }
    });
}
</script>
@endsection
