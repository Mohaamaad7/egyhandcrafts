@extends('layouts.app')

@section('title', 'دليل الحرف التراثية بمحافظة المنوفية | جامعة مدينة السادات')

@section('content')
{{-- ============================== --}}
{{-- CRAFTS DIRECTORY — HERO BANNER  --}}
{{-- ============================== --}}
<section class="bg-primary py-14 md:py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="arabesque-pattern w-full h-full"></div>
    </div>
    <div class="container mx-auto px-4 lg:px-8 text-center relative z-10" data-aos="fade-up">
        <div class="inline-flex items-center gap-2 bg-accent/20 text-accent border border-accent/30 px-4 py-1.5 rounded-full text-sm font-bold mb-5">
            <i class="fas fa-scroll"></i>
            <span>التوثيق الميداني والأكاديمي للحرف التقليدية</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-serif text-white leading-tight mb-4">
            دليل الحرف التراثية بالمنوفية
        </h1>
        <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
            استكشف روائع الصناعات اليدوية والتراثية الموثقة علمياً وميدانياً بمحافظة المنوفية — إرث حضاري عميق وأيادٍ مصرية ماهرة تصنع الجمال عبر الأجيال.
        </p>
        <div class="mt-6 inline-flex items-center gap-2 bg-white/10 text-gold px-4 py-1.5 rounded-full text-sm font-bold border border-gold/30">
            <i class="fas fa-certificate"></i>
            <span>{{ $crafts->total() }} حرفة تراثية موثقة</span>
        </div>
    </div>
</section>

{{-- ============================== --}}
{{-- CRAFTS GRID                    --}}
{{-- ============================== --}}
<section class="py-12 md:py-20 bg-background">
    <div class="container mx-auto px-4 lg:px-8">

        @if($crafts->isEmpty())
            <div class="text-center py-20 text-gray-400 bg-white rounded-3xl p-12 border border-gray-100 shadow-sm">
                <i class="fas fa-tools text-6xl mb-4 block text-accent/40"></i>
                <h3 class="text-2xl font-bold font-serif text-primary mb-2">لا توجد حرف مضافة حالياً</h3>
                <p class="text-gray-500">يتم العمل حالياً على إضافة وتوثيق المزيد من الحرف التراثية. تابعنا قريباً!</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($crafts as $craft)
                <article class="interactive-card bg-white rounded-3xl overflow-hidden shadow-card-soft hover:shadow-card-hover border border-gray-100/80 flex flex-col group transition-all duration-300"
                         data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">

                    {{-- Card Image Container --}}
                    <div class="relative h-60 overflow-hidden bg-primary/10">
                        <img src="{{ $craft->cover_image_url }}"
                             alt="{{ $craft->title }}"
                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/card_guide.jpg') }}';">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/70 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>

                        {{-- Location Badge --}}
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1.5 bg-primary/85 backdrop-blur-md text-white text-xs font-bold px-3.5 py-1.5 rounded-full border border-white/20 shadow-sm">
                                <i class="fas fa-map-marker-alt text-accent text-xs"></i>
                                <span>{{ $craft->location }}</span>
                            </span>
                        </div>

                        {{-- Heritage Badge --}}
                        <div class="absolute bottom-4 right-4">
                            <span class="inline-flex items-center gap-1 bg-accent/90 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg backdrop-blur-sm">
                                <i class="fas fa-gem text-[10px]"></i>
                                <span>تراث أصيل</span>
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6 flex flex-col flex-grow">
                        <h2 class="text-2xl font-bold font-serif text-primary mb-3 group-hover:text-accent transition-colors duration-300 line-clamp-1">
                            {{ $craft->title }}
                        </h2>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6 line-clamp-3 flex-grow font-sans">
                            {{ $craft->short_description }}
                        </p>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <a href="{{ route('crafts.show', $craft->slug) }}"
                               class="inline-flex items-center gap-2 bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-accent transition-all duration-300 shadow-sm group-hover:shadow-md">
                                <span>استكشف الحرفة</span>
                                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                            </a>
                            <span class="text-xs text-gray-400 font-medium">
                                <i class="fas fa-calendar-alt text-accent/70 ml-1"></i>
                                {{ $craft->created_at ? $craft->created_at->format('Y') : date('Y') }}
                            </span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($crafts->hasPages())
                <div class="mt-14 flex justify-center">
                    {{ $crafts->links() }}
                </div>
            @endif
        @endif

    </div>
</section>
@endsection
