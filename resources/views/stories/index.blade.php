@extends('layouts.app')

@section('title', 'قصص وشهادات الحرفيين | توثيق الحرف التراثية بمحافظة المنوفية')

@section('content')
{{-- ============================== --}}
{{-- STORIES — HERO BANNER          --}}
{{-- ============================== --}}
<section class="relative py-16 md:py-24 overflow-hidden text-white"
         style="background: linear-gradient(180deg, rgba(14, 27, 45, 0.88) 0%, rgba(26, 47, 76, 0.92) 60%, rgba(14, 27, 45, 0.98) 100%), url('{{ asset('assets/images/HeroBG.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    {{-- Ambient Glow Elements --}}
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-accent/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-gold/15 rounded-full blur-3xl pointer-events-none"></div>

    {{-- Arabesque Pattern Watermark --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="arabesque-pattern w-full h-full"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 text-center relative z-10" data-aos="fade-up">
        <div class="inline-flex items-center gap-2 bg-accent/20 text-accent border border-accent/40 backdrop-blur-md px-4 py-1.5 rounded-full text-sm font-bold mb-5 shadow-sm">
            <i class="fas fa-microphone-alt"></i>
            <span>شهادات ميدانية موثقة من أفواه صُنّاع التراث</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-serif text-white leading-tight mb-4 drop-shadow-lg">
            قصص وشهادات الحرفيين
        </h1>
        <p class="text-gray-200 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-light">
            أصوات حقيقية ونظرات عميقة من داخل الورش — حكايات الأساتذة والرواد الذين حملوا إرث المنوفية الحرفي على أكتافهم عبر عقود من الزمن.
        </p>
        <div class="mt-6 inline-flex items-center gap-2 bg-white/10 text-gold px-4 py-1.5 rounded-full text-sm font-bold border border-gold/30 backdrop-blur-md shadow-sm">
            <i class="fas fa-certificate"></i>
            <span>{{ $stories->total() }} شهادة ميدانية موثقة</span>
        </div>
    </div>
</section>

{{-- ============================== --}}
{{-- STORIES GRID                   --}}
{{-- ============================== --}}
<section class="py-12 md:py-20 bg-background">
    <div class="container mx-auto px-4 lg:px-8">

        @if($stories->isEmpty())
            <div class="text-center py-20 text-gray-400 bg-white rounded-3xl p-12 border border-gray-100 shadow-sm">
                <i class="fas fa-microphone-slash text-6xl mb-4 block text-accent/40"></i>
                <h3 class="text-2xl font-bold font-serif text-primary mb-2">لا توجد شهادات مضافة حالياً</h3>
                <p class="text-gray-500">يتم العمل حالياً على توثيق المزيد من قصص الحرفيين الميدانية. تابعنا قريباً!</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($stories as $story)
                <article class="interactive-card bg-white rounded-3xl overflow-hidden shadow-card-soft hover:shadow-card-hover border border-gray-100/80 flex flex-col group transition-all duration-300"
                         data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">

                    {{-- Card Image Container --}}
                    <div class="relative h-60 overflow-hidden bg-primary/10">
                        <img src="{{ $story->photo_url }}"
                             alt="{{ $story->craftsman_name }}"
                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/HeroBG.jpg') }}';">

                        <div class="absolute inset-0 bg-gradient-to-t from-primary/70 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>

                        {{-- Media Badges —— top-left --}}
                        <div class="absolute top-4 left-4 flex flex-col gap-1.5">
                            @if($story->has_video)
                                <span class="inline-flex items-center gap-1 bg-red-600/90 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg backdrop-blur-sm">
                                    <i class="fas fa-video text-[10px]"></i>
                                    <span>توثيق مرئي</span>
                                </span>
                            @endif
                            @if($story->has_audio)
                                <span class="inline-flex items-center gap-1 bg-amber-600/90 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg backdrop-blur-sm">
                                    <i class="fas fa-headphones text-[10px]"></i>
                                    <span>تسجيل صوتي</span>
                                </span>
                            @endif
                        </div>

                        {{-- Heritage Badge —— bottom-right --}}
                        <div class="absolute bottom-4 right-4">
                            <span class="inline-flex items-center gap-1 bg-accent/90 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg backdrop-blur-sm">
                                <i class="fas fa-gem text-[10px]"></i>
                                <span>شهادة ميدانية</span>
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6 flex flex-col flex-grow">
                        {{-- Craftsman Name --}}
                        <h2 class="text-2xl font-bold font-serif text-primary mb-1 group-hover:text-accent transition-colors duration-300 line-clamp-1">
                            {{ $story->craftsman_name }}
                        </h2>

                        {{-- Role Badge --}}
                        <div class="mb-3">
                            <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary text-xs font-bold px-3 py-1 rounded-full">
                                <i class="fas fa-award text-accent text-[10px]"></i>
                                <span>{{ $story->craftsman_role }}</span>
                            </span>
                        </div>

                        {{-- Excerpt --}}
                        <p class="text-gray-600 text-sm leading-relaxed mb-6 line-clamp-3 flex-grow font-sans">
                            {{ $story->excerpt_text }}
                        </p>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <a href="{{ route('stories.show', $story->slug) }}"
                               class="inline-flex items-center gap-2 bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-accent transition-all duration-300 shadow-sm group-hover:shadow-md">
                                <span>قراءة الشهادة كاملة</span>
                                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                            </a>
                            <span class="text-xs text-gray-400 font-medium">
                                <i class="fas fa-calendar-alt text-accent/70 ml-1"></i>
                                {{ $story->created_at->format('Y/m/d') }}
                            </span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($stories->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $stories->links() }}
                </div>
            @endif
        @endif

    </div>
</section>
@endsection
