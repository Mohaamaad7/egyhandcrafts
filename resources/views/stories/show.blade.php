@extends('layouts.app')

@section('title', $story->craftsman_name . ' — ' . $story->title . ' | قصص وشهادات الحرفيين')

@section('content')
{{-- ============================== --}}
{{-- STORY DETAIL — HERO SECTION    --}}
{{-- ============================== --}}
<section class="relative py-14 md:py-20 overflow-hidden text-white"
         style="background: linear-gradient(180deg, rgba(14, 27, 45, 0.88) 0%, rgba(26, 47, 76, 0.93) 55%, rgba(14, 27, 45, 0.98) 100%), url('{{ $story->photo_url }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

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
            <a href="{{ route('stories.index') }}" class="hover:text-accent transition-colors">
                <span>قصص وشهادات الحرفيين</span>
            </a>
            <span class="text-gray-500">/</span>
            <span class="text-gold font-bold truncate max-w-xs">{{ $story->craftsman_name }}</span>
        </nav>

        {{-- Title and Badges --}}
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2 bg-accent/20 text-accent border border-accent/40 px-3.5 py-1 rounded-full text-xs md:text-sm font-bold mb-4">
                <i class="fas fa-microphone-alt text-gold"></i>
                <span>شهادة ميدانية موثقة</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-serif leading-tight mb-4">
                {{ $story->craftsman_name }}
            </h1>

            <p class="text-xl md:text-2xl text-gray-200 font-light mb-6">
                {{ $story->title }}
            </p>

            <div class="flex flex-wrap items-center gap-3 text-sm">
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white border border-white/20 px-4 py-2 rounded-xl">
                    <i class="fas fa-award text-accent"></i>
                    <span><strong>الصفة:</strong> {{ $story->craftsman_role }}</span>
                </span>
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white border border-white/20 px-4 py-2 rounded-xl">
                    <i class="fas fa-university text-gold"></i>
                    <span>جامعة مدينة السادات</span>
                </span>
                @if($story->has_video)
                    <span class="inline-flex items-center gap-2 bg-red-600/30 backdrop-blur-md text-white border border-red-400/30 px-4 py-2 rounded-xl">
                        <i class="fas fa-video text-red-300"></i>
                        <span>توثيق مرئي</span>
                    </span>
                @endif
                @if($story->has_audio)
                    <span class="inline-flex items-center gap-2 bg-amber-600/30 backdrop-blur-md text-white border border-amber-400/30 px-4 py-2 rounded-xl">
                        <i class="fas fa-headphones text-amber-300"></i>
                        <span>تسجيل صوتي</span>
                    </span>
                @endif
            </div>
        </div>

    </div>
</section>

{{-- ============================== --}}
{{-- STORY DETAIL — MAIN CONTENT    --}}
{{-- ============================== --}}
<section class="py-12 md:py-16 bg-background">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- MAIN COLUMN (8 of 12) --}}
            <main class="lg:col-span-8 flex flex-col gap-8 min-w-0">

                {{-- Executive Summary Quote Card --}}
                <div class="bg-white border-r-4 border-accent rounded-3xl p-6 md:p-8 shadow-card-soft relative overflow-hidden" data-aos="fade-up">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center flex-shrink-0 text-xl">
                            <i class="fas fa-quote-right"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold font-serif text-primary mb-2">نبذة عن الشهادة</h2>
                            <p class="text-gray-700 text-lg md:text-xl leading-loose font-sans">
                                {{ $story->excerpt_text }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rich Content Article (CKEditor HTML) --}}
                <article class="bg-white rounded-3xl p-6 sm:p-8 md:p-12 shadow-card-soft border border-gray-100/80 min-w-0" data-aos="fade-up">
                    <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed font-sans min-w-0 w-full">
                        {!! $story->content !!}
                    </div>
                </article>

                {{-- ============================================================ --}}
                {{-- CONDITIONAL MULTIMEDIA — STRICT ZERO-GHOST-SPACE ENFORCEMENT --}}
                {{-- ============================================================ --}}
                @if($story->has_audio || $story->has_video)
                    <div class="flex flex-col gap-6" data-aos="fade-up">
                        {{-- YouTube Video Embed (only if video exists) --}}
                        @if($story->has_video)
                            <div class="bg-white rounded-3xl overflow-hidden shadow-card-soft border border-gray-100/80">
                                <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-video text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold font-serif text-primary">التوثيق المرئي للشهادة</h3>
                                        <p class="text-xs text-gray-400">فيديو ميداني من داخل ورشة الحرفي</p>
                                    </div>
                                </div>
                                <div class="relative w-full" style="padding-top: 56.25%;">
                                    <iframe src="{{ $story->youtube_embed_url }}"
                                            class="absolute inset-0 w-full h-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                            loading="lazy"></iframe>
                                </div>
                            </div>
                        @endif

                        {{-- Audio Player (only if audio exists) --}}
                        @if($story->has_audio)
                            <div class="bg-white rounded-3xl overflow-hidden shadow-card-soft border border-gray-100/80 p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-headphones text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold font-serif text-primary">التسجيل الصوتي الميداني</h3>
                                        <p class="text-xs text-gray-400">مقطع صوتي موثّق من داخل الورشة</p>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-l from-primary/5 to-accent/5 rounded-2xl p-4 border border-gray-100">
                                    <audio controls class="w-full" preload="metadata">
                                        <source src="{{ $story->audio_file_url }}" type="audio/mpeg">
                                        المتصفح لا يدعم مشغل الصوتيات.
                                    </audio>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Social Share & Action Toolbar --}}
                <div class="bg-white rounded-2xl p-6 shadow-card-soft border border-gray-100 flex flex-wrap items-center justify-between gap-4" data-aos="fade-up">
                    <div class="flex items-center gap-2 text-primary font-bold">
                        <i class="fas fa-share-alt text-accent text-lg"></i>
                        <span>مشاركة هذه الشهادة:</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        {{-- WhatsApp --}}
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($story->craftsman_name . ' — ' . $story->title . ' | ' . url()->current()) }}"
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
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($story->craftsman_name . ' — ' . $story->title) }}&url={{ urlencode(url()->current()) }}"
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

                {{-- Previous / Next Story Navigation --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2" data-aos="fade-up">
                    @if(isset($prevStory) && $prevStory)
                        <a href="{{ route('stories.show', $prevStory->slug) }}"
                           class="bg-white p-5 rounded-2xl border border-gray-100 shadow-card-soft hover:shadow-card-hover hover:border-accent/40 transition-all flex items-center gap-4 group">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 group-hover:bg-accent group-hover:text-white transition-colors">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="overflow-hidden">
                                <span class="text-xs text-gray-400 block mb-0.5">الشهادة السابقة</span>
                                <h4 class="text-sm font-bold font-serif text-primary truncate group-hover:text-accent transition-colors">
                                    {{ $prevStory->craftsman_name }}
                                </h4>
                            </div>
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if(isset($nextStory) && $nextStory)
                        <a href="{{ route('stories.show', $nextStory->slug) }}"
                           class="bg-white p-5 rounded-2xl border border-gray-100 shadow-card-soft hover:shadow-card-hover hover:border-accent/40 transition-all flex items-center gap-4 group justify-end text-left">
                            <div class="overflow-hidden">
                                <span class="text-xs text-gray-400 block mb-0.5">الشهادة التالية</span>
                                <h4 class="text-sm font-bold font-serif text-primary truncate group-hover:text-accent transition-colors">
                                    {{ $nextStory->craftsman_name }}
                                </h4>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 group-hover:bg-accent group-hover:text-white transition-colors">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                        </a>
                    @else
                        <div></div>
                    @endif
                </div>

            </main>

            {{-- SIDEBAR COLUMN (4 of 12) --}}
            <aside class="lg:col-span-4 flex flex-col gap-6 lg:sticky lg:top-8">

                {{-- Craftsman Identity Card --}}
                <div class="bg-white rounded-3xl overflow-hidden shadow-card-soft border border-gray-100/90 text-center" data-aos="fade-up">
                    <div class="h-2.5 bg-gradient-to-l from-accent via-gold to-primary"></div>
                    <div class="p-6 sm:p-8">
                        {{-- Consolidated Artisan Portrait with Elegant Frame & Verified Badge --}}
                        <div class="relative w-36 h-36 mx-auto mb-5">
                            <div class="w-full h-full rounded-2xl overflow-hidden border-2 border-accent/40 shadow-md ring-4 ring-primary/5 group">
                                <img src="{{ $story->photo_url }}" alt="{{ $story->craftsman_name }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/HeroBG.jpg') }}';">
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-accent text-white w-7 h-7 rounded-full flex items-center justify-center shadow-md border-2 border-white text-xs"
                                 title="حرفي موثق ميدانياً">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>

                        <div class="inline-flex items-center gap-1.5 bg-accent/10 text-accent text-xs font-bold px-3 py-1 rounded-full mb-3">
                            <i class="fas fa-certificate text-gold text-[10px]"></i>
                            <span>بطاقة توثيق الحرفي</span>
                        </div>

                        <h3 class="text-2xl font-bold font-serif text-primary mb-1">{{ $story->craftsman_name }}</h3>
                        <p class="text-sm text-accent font-bold mb-4">{{ $story->craftsman_role }}</p>

                        <div class="flex justify-center gap-2 flex-wrap pt-4 border-t border-gray-100">
                            @if($story->has_video)
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fas fa-video text-[10px]"></i> توثيق مرئي
                                </span>
                            @endif
                            @if($story->has_audio)
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fas fa-headphones text-[10px]"></i> تسجيل صوتي
                                </span>
                            @endif
                            @if(!$story->has_video && !$story->has_audio)
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fas fa-pen-fancy text-[10px]"></i> شهادة نصية
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Other Documented Stories --}}
                @if($otherStories->isNotEmpty())
                <div class="bg-white rounded-3xl p-6 shadow-card-soft border border-gray-100" data-aos="fade-up">
                    <h3 class="text-lg font-bold font-serif text-primary mb-4 flex items-center gap-2">
                        <i class="fas fa-users text-accent"></i>
                        <span>شهادات أخرى موثقة</span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($otherStories as $otherStory)
                            <a href="{{ route('stories.show', $otherStory->slug) }}"
                               class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                                <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200">
                                    <img src="{{ $otherStory->photo_url }}" alt="{{ $otherStory->craftsman_name }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/images/HeroBG.jpg') }}';">
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-bold text-primary truncate group-hover:text-accent transition-colors">
                                        {{ $otherStory->craftsman_name }}
                                    </h4>
                                    <p class="text-xs text-gray-400 truncate">{{ $otherStory->craftsman_role }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- University Project Card --}}
                <div class="bg-gradient-to-bl from-primary to-primary-dark rounded-3xl p-6 text-white shadow-lg" data-aos="fade-up">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                            <i class="fas fa-university text-gold text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold font-serif text-lg">جامعة مدينة السادات</h3>
                            <p class="text-gray-300 text-xs">كلية السياحة والفنادق</p>
                        </div>
                    </div>
                    <p class="text-gray-200 text-sm leading-relaxed mb-4">
                        مشروع أكاديمي ميداني لتوثيق ودراسة الحرف التراثية والصناعات التقليدية بمحافظة المنوفية.
                    </p>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-xs font-bold px-4 py-2 rounded-xl hover:bg-white/25 transition-colors border border-white/20">
                        <i class="fas fa-home"></i>
                        <span>الصفحة الرئيسية</span>
                    </a>
                </div>

            </aside>

        </div>
    </div>
</section>
@endsection
