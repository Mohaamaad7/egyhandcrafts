@extends('layouts.app')

@section('title', 'مشروع توثيق الحرف التراثية بمحافظة المنوفية | جامعة مدينة السادات')

@section('content')
<!-- ========================================== -->
<!-- 1. HERO SECTION (Min Height: 80vh)         -->
<!-- ========================================== -->
<section class="hero-banner relative min-h-[80vh] flex items-center justify-center text-center overflow-hidden py-16 px-4"
    style="background: linear-gradient(180deg, rgba(14, 27, 45, 0.85) 0%, rgba(26, 47, 76, 0.90) 60%, rgba(14, 27, 45, 0.96) 100%), url('{{ asset('assets/images/HeroBG.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    <!-- Ambient Glow / Blur Elements -->
    <div class="absolute -top-20 -right-20 w-80 h-80 bg-accent/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-primary/40 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto relative z-10 max-w-4xl">

        <!-- Cultural Badge -->
        <div data-aos="fade-down" data-aos-duration="1000"
            class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-primary-dark/80 border border-accent/40 backdrop-blur-md text-accent text-sm md:text-base font-semibold mb-6 shadow-lg">
            <i class="fas fa-feather-alt"></i>
            <span>أطلس التراث والصناعات التقليدية</span>
        </div>

        <!-- Main Title Centered (White / Gold) with Smooth Fade-in Animation -->
        <h1 data-aos="zoom-in" data-aos-duration="1100"
            class="font-serif text-3xl sm:text-5xl md:text-6xl font-bold text-white leading-tight md:leading-tight mb-6 drop-shadow-2xl">
            بوابة توثيق الحرف التراثية<br>
            <span class="text-gradient-gold font-serif mt-3 inline-block">بمحافظة المنوفية</span>
        </h1>

        <!-- Ornamental Divider -->
        <div data-aos="fade-up" data-aos-delay="200" class="flex items-center justify-center gap-3 my-6">
            <div class="h-0.5 w-16 md:w-24 bg-gradient-to-l from-accent to-transparent"></div>
            <div class="w-2.5 h-2.5 rotate-45 bg-accent"></div>
            <i class="fas fa-gem text-accent text-xs"></i>
            <div class="w-2.5 h-2.5 rotate-45 bg-accent"></div>
            <div class="h-0.5 w-16 md:w-24 bg-gradient-to-r from-accent to-transparent"></div>
        </div>

        <!-- Subtitle Description -->
        <p data-aos="fade-up" data-aos-delay="300"
            class="text-gray-200 text-base sm:text-xl font-light leading-relaxed max-w-2xl mx-auto mb-8">
            بوابة رقمية وبحثية شاملة لتوثيق وأرشفة كنوز الحرف اليدوية والهوية الثقافية بمحافظة المنوفية، برعاية جامعة
            مدينة السادات.
        </p>

        <!-- CTA Buttons -->
        <div data-aos="fade-up" data-aos-delay="400" class="flex flex-wrap justify-center gap-4">
            <a href="#crafts-grid"
                class="inline-flex items-center gap-2.5 bg-accent hover:bg-orange-600 text-white font-bold text-base px-7 py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <span>استكشف أقسام المشروع</span>
                <i class="fas fa-arrow-down text-sm"></i>
            </a>
            <a href="#interactive-map"
                class="inline-flex items-center gap-2.5 bg-primary-dark/80 hover:bg-primary text-white font-semibold text-base px-7 py-3.5 rounded-xl border border-accent/40 hover:border-accent backdrop-blur-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="fas fa-map-marked-alt text-accent"></i>
                <span>الخريطة التفاعلية</span>
            </a>
        </div>

    </div>
</section>
<!-- ========================================== -->
<!-- 2. THE CRAFTS GRID (Horizontal Flex 2x2)   -->
<!-- ========================================== -->
<main id="crafts-grid" class="container mx-auto px-4 lg:px-8 py-16 flex-grow">

    <!-- Section Heading -->
    <div class="text-center max-w-2xl mx-auto mb-14" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-serif text-primary font-bold leading-tight">
            أقسام ومحاور التوثيق
        </h2>
        <div class="w-20 h-1 bg-accent mx-auto mt-4 rounded-full"></div>
    </div>

    <!-- 2x2 Grid with Horizontal Flex Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">

        <!-- ================= CARD 1: Documented Crafts ================= -->
        <div id="documented-crafts" data-aos="fade-up" data-aos-delay="100"
            class="interactive-card group bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_10px_25px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_35px_-5px_rgba(26,47,76,0.12)] hover:border-accent/30 cursor-pointer flex flex-col sm:flex-row items-center gap-5 transition-all duration-300 transform hover:-translate-y-2">

            <!-- Right Side: Fixed Thumbnail Image (130x130) -->
            <div
                class="w-[130px] h-[130px] sm:w-[140px] sm:h-[140px] flex-shrink-0 rounded-2xl overflow-hidden shadow-md border border-gray-200/80 bg-gray-100 relative group-hover:border-accent/40 transition-all duration-300">
                <img src="{{ asset('assets/images/card_serma.png') }}" alt="الحرف الموثقة بالمشروع"
                    class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1606744837616-56c9a5c6a6eb?q=80&w=600&auto=format&fit=crop';">
            </div>

            <!-- Left Side: Text Content -->
            <div class="flex-1 flex flex-col justify-between self-stretch text-right">
                <div>
                    <!-- Title with Small Icon -->
                    <div class="flex items-center gap-2.5 mb-2">
                        <span
                            class="w-8 h-8 rounded-full bg-accent/15 text-accent flex items-center justify-center text-sm flex-shrink-0 group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-scroll"></i>
                        </span>
                        <h3
                            class="text-xl sm:text-2xl font-bold text-accent font-serif group-hover:text-orange-600 transition-colors">
                            الحرف الموثقة بالمشروع
                        </h3>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base font-sans mb-3">
                        تضم الحرف التي تم توثيقها بشكل كامل (السيرما والصدف). تحتوي كل حرفة على التفاصيل الكاملة بناءً
                        على عناوين الاستمارة البحثية.
                    </p>
                </div>

                <!-- Read More Link -->
                <div
                    class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs sm:text-sm font-bold text-primary group-hover:text-accent transition-colors mt-auto">
                    <span>توثيق حرفتي الصدف و السيرما</span>
                    <i class="fas fa-arrow-left transition-transform duration-300 group-hover:-translate-x-1.5"></i>
                </div>
            </div>

        </div>
        <!-- ================= CARD 2: Handcrafts Directory ============= -->
        <div id="crafts-directory" data-aos="fade-up" data-aos-delay="200"
            class="interactive-card group bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_10px_25px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_35px_-5px_rgba(26,47,76,0.12)] hover:border-accent/30 cursor-pointer flex flex-col sm:flex-row items-center gap-5 transition-all duration-300 transform hover:-translate-y-2">

            <!-- Right Side: Fixed Thumbnail Image (130x130) -->
            <div
                class="w-[130px] h-[130px] sm:w-[140px] sm:h-[140px] flex-shrink-0 rounded-2xl overflow-hidden shadow-md border border-gray-200/80 bg-gray-100 relative group-hover:border-accent/40 transition-all duration-300">
                <img src="{{ asset('assets/images/card_guide.jpg') }}" alt="دليل الحرف اليدوية"
                    class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=600&auto=format&fit=crop';">
            </div>

            <!-- Left Side: Text Content -->
            <div class="flex-1 flex flex-col justify-between self-stretch text-right">
                <div>
                    <!-- Title with Small Icon -->
                    <div class="flex items-center gap-2.5 mb-2">
                        <span
                            class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm flex-shrink-0 group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-book-open"></i>
                        </span>
                        <h3
                            class="text-xl sm:text-2xl font-bold text-primary font-serif group-hover:text-accent transition-colors">
                            دليل الحرف اليدوية بالمنوفية
                        </h3>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base font-sans mb-3">
                        قائمة شاملة تضم كافة الحرف التراثية المتواجدة بالمحافظة، مع ذكر نبذة مختصرة عن كل حرفة.
                    </p>
                </div>

                <!-- Read More Link -->
                <a href="{{ url('/crafts') }}"
                    class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs sm:text-sm font-bold text-primary group-hover:text-accent transition-colors mt-auto">
                    <span>تصفح الدليل الشامل</span>
                    <i class="fas fa-arrow-left transition-transform duration-300 group-hover:-translate-x-1.5"></i>
                </a>
            </div>

        </div>
        <!-- ================= CARD 3: Interactive Map ================= -->
        <div id="interactive-map" data-aos="fade-up" data-aos-delay="300"
            class="interactive-card group bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_10px_25px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_35px_-5px_rgba(26,47,76,0.12)] hover:border-accent/30 cursor-pointer flex flex-col sm:flex-row items-center gap-5 transition-all duration-300 transform hover:-translate-y-2">

            <!-- Right Side: Fixed Thumbnail Image (130x130) -->
            <div
                class="w-[130px] h-[130px] sm:w-[140px] sm:h-[140px] flex-shrink-0 rounded-2xl overflow-hidden shadow-md border border-gray-200/80 bg-gray-100 relative group-hover:border-accent/40 transition-all duration-300">
                <img src="{{ asset('assets/images/card_map.jpg') }}" alt="الخريطة التفاعلية"
                    class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=600&auto=format&fit=crop';">
            </div>

            <!-- Left Side: Text Content -->
            <div class="flex-1 flex flex-col justify-between self-stretch text-right">
                <div>
                    <!-- Title with Small Icon -->
                    <div class="flex items-center gap-2.5 mb-2">
                        <span
                            class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm flex-shrink-0 group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-map-marked-alt"></i>
                        </span>
                        <h3
                            class="text-xl sm:text-2xl font-bold text-primary font-serif group-hover:text-accent transition-colors">
                            الخريطة التفاعلية
                        </h3>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base font-sans mb-3">
                        خريطة تفاعلية توضح التوزيع الجغرافي لانتشار الحرف التراثية وأماكن الورش.
                    </p>
                </div>

                <!-- Read More Link -->
                <div
                    class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs sm:text-sm font-bold text-primary group-hover:text-accent transition-colors mt-auto">
                    <span>استكشاف الخريطة الجغرافية</span>
                    <i class="fas fa-arrow-left transition-transform duration-300 group-hover:-translate-x-1.5"></i>
                </div>
            </div>

        </div>
        <!-- ================= CARD 4: Craftsmen Stories ================= -->
        <div id="artisans-stories" data-aos="fade-up" data-aos-delay="400"
            class="interactive-card group bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_10px_25px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_35px_-5px_rgba(26,47,76,0.12)] hover:border-accent/30 cursor-pointer flex flex-col sm:flex-row items-center gap-5 transition-all duration-300 transform hover:-translate-y-2">

            <!-- Right Side: Fixed Thumbnail Image (130x130) -->
            <div
                class="w-[130px] h-[130px] sm:w-[140px] sm:h-[140px] flex-shrink-0 rounded-2xl overflow-hidden shadow-md border border-gray-200/80 bg-gray-100 relative group-hover:border-accent/40 transition-all duration-300">
                <img src="{{ asset('assets/images/card_stories.jpg') }}" alt="قصص الحرفيين"
                    class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1534447677768-be436bb09401?q=80&w=600&auto=format&fit=crop';">
            </div>

            <!-- Left Side: Text Content -->
            <div class="flex-1 flex flex-col justify-between self-stretch text-right">
                <div>
                    <!-- Title with Small Icon -->
                    <div class="flex items-center gap-2.5 mb-2">
                        <span
                            class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm flex-shrink-0 group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-users"></i>
                        </span>
                        <h3
                            class="text-xl sm:text-2xl font-bold text-primary font-serif group-hover:text-accent transition-colors">
                            قصص الحرفيين
                        </h3>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base font-sans mb-3">
                        مساحة مخصصة لعرض الحكايات الشخصية وتاريخ رواد الحرف اليدوية.
                    </p>
                </div>

                <!-- Read More Link -->
                <div
                    class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs sm:text-sm font-bold text-primary group-hover:text-accent transition-colors mt-auto">
                    <span>مشاهدة شهادات وقصص الحرفيين</span>
                    <i class="fas fa-arrow-left transition-transform duration-300 group-hover:-translate-x-1.5"></i>
                </div>
            </div>

        </div>

    </div>

</main>
@endsection




