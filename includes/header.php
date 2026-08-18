<?php
/**
 * Header Template - مشروع توثيق الحرف التراثية بمحافظة المنوفية
 * كلية السياحة والفنادق - جامعة مدينة السادات
 */
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشروع توثيق الحرف التراثية بمحافظة المنوفية | جامعة مدينة السادات</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="البوابة الرسمية لتوثيق ودراسة الحرف التراثية والصناعات التقليدية بمحافظة المنوفية - كلية السياحة والفنادق، جامعة مدينة السادات.">
    <meta name="theme-color" content="#1A2F4C">

    <!-- Google Fonts: Amiri & Tajawal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS (Animate On Scroll) CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1A2F4C',       // Deep Navy Blue
                        'primary-dark': '#0E1B2D',
                        'primary-light': '#264268',
                        accent: '#E67E22',        // Heritage Amber / Orange
                        gold: '#D4AF37',          // Heritage Gold
                        background: '#f4f6f8',    // Soft Off-White
                    },
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                        serif: ['Amiri', 'serif'],
                    },
                    boxShadow: {
                        'card-soft': '0 10px 25px rgba(0, 0, 0, 0.05)',
                        'card-hover': '0 20px 35px -5px rgba(26, 47, 76, 0.12), 0 10px 20px -5px rgba(230, 126, 34, 0.15)',
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Base styles */
        body {
            background-color: #f4f6f8;
            color: #1A2F4C;
            font-family: 'Tajawal', sans-serif;
        }

        /* Arabesque geometric subtle watermark pattern */
        .arabesque-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l7.07 12.93L51.21 8.79l-4.14 14.14L60 30l-12.93 7.07 4.14 14.14-14.14-4.14L30 60l-7.07-12.93-14.14 4.14 4.14-14.14L0 30l12.93-7.07-4.14-14.14 14.14 4.14z' fill='%231A2F4C' fill-opacity='0.02' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Glassmorphic Sticky Navbar */
        .glass-navbar {
            background: rgba(26, 47, 76, 0.90);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(230, 126, 34, 0.25);
        }

        /* Hero Background */
        .hero-banner {
            background: linear-gradient(180deg, rgba(14, 27, 45, 0.85) 0%, rgba(26, 47, 76, 0.90) 60%, rgba(14, 27, 45, 0.96) 100%), 
                        url('https://images.unsplash.com/photo-1572252009286-268acec5ca0a?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Gold Gradient Text */
        .text-gradient-gold {
            background: linear-gradient(135deg, #FFF6D6 0%, #D4AF37 50%, #E67E22 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Interactive card hover */
        .interactive-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .interactive-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body class="arabesque-pattern flex flex-col min-h-screen antialiased text-gray-800 selection:bg-accent selection:text-white">

    <!-- ========================================== -->
    <!-- 1. TOP UTILITY BAR (Language Toggle)       -->
    <!-- ========================================== -->
    <div class="bg-primary-dark text-gray-300 py-2 border-b border-primary/40 relative z-50">
        <div class="container mx-auto px-4 lg:px-8 flex justify-between items-center text-xs md:text-sm">
            
            <div class="flex items-center gap-2 text-gray-300">
                <span class="inline-flex items-center gap-1 bg-primary px-2.5 py-0.5 rounded-full text-gold text-xs border border-gold/20 font-medium">
                    <i class="fas fa-university text-[11px]"></i> مشروع بحثي وتوثيقي
                </span>
                <span class="hidden sm:inline text-gray-400">|</span>
                <span class="hidden sm:inline">جامعة مدينة السادات - كلية السياحة والفنادق</span>
            </div>

            <!-- Language Toggle (English / عربي) -->
            <div>
                <a href="?lang=en" class="inline-flex items-center gap-2 text-accent hover:text-white font-bold transition-colors duration-300 px-3 py-1 rounded hover:bg-primary">
                    <i class="fas fa-globe"></i>
                    <span>English / عربي</span>
                </a>
            </div>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. CENTERED HERITAGE FRAME LOGOS SECTION   -->
    <!-- ========================================== -->
    <header class="bg-white py-4 shadow-sm border-b border-gray-100 relative z-40">
        <div class="container mx-auto px-4 lg:px-8 flex justify-center items-center">
            
            <!-- The "Heritage Frame" Container -->
            <div class="inline-flex flex-wrap md:flex-nowrap items-center justify-center gap-6 md:gap-8 bg-[#faf8f5] border border-[rgba(230,126,34,0.3)] rounded-2xl py-3.5 px-6 md:px-10 shadow-[0_4px_15px_rgba(0,0,0,0.05)]">
                
                <!-- Logo 1: Project Logo (Right in RTL) -->
                <div class="flex items-center gap-3 group">
                    <img src="assets/images/project_logo.png" 
                         alt="شعار مشروع توثيق الحرف التراثية بالمنوفية" 
                         class="max-h-[75px] w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                         onerror="this.onerror=null; this.src='https://placehold.co/180x75/1A2F4C/FFFFFF?text=Project+Logo';">
                    <div class="text-right">
                        <span class="text-[11px] font-bold text-accent block">مشروع التوثيق</span>
                        <h2 class="text-sm md:text-base font-bold text-primary font-serif leading-tight">توثيق الحرف التراثية</h2>
                        <span class="text-[10px] md:text-[11px] text-gray-500">بمحافظة المنوفية</span>
                    </div>
                </div>

                <!-- Elegant Vertical Divider 1 -->
                <div class="hidden md:block w-[1px] h-14 bg-gray-300"></div>

                <!-- Logo 2: College Logo (Center in RTL) -->
                <div class="flex items-center gap-3 group">
                    <img src="assets/images/colledge_logo.png" 
                         alt="شعار كلية السياحة والفنادق" 
                         class="max-h-[75px] w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                         onerror="this.onerror=null; this.src='https://placehold.co/180x75/1A2F4C/FFFFFF?text=Faculty+Logo';">
                    <div class="text-right">
                        <span class="text-[11px] font-bold text-accent block">الجهة المنفذة</span>
                        <h2 class="text-sm md:text-base font-bold text-primary font-serif leading-tight">كلية السياحة والفنادق</h2>
                        <span class="text-[10px] md:text-[11px] text-gray-500">جامعة مدينة السادات</span>
                    </div>
                </div>

                <!-- Elegant Vertical Divider 2 -->
                <div class="hidden md:block w-[1px] h-14 bg-gray-300"></div>

                <!-- Logo 3: University Logo (Left in RTL) -->
                <div class="flex items-center gap-3 group">
                    <img src="assets/images/university_logo.png" 
                         alt="شعار جامعة مدينة السادات" 
                         class="max-h-[75px] w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                         onerror="this.onerror=null; this.src='https://placehold.co/180x75/1A2F4C/FFFFFF?text=University+Logo';">
                    <div class="text-right">
                        <span class="text-[11px] font-bold text-accent block">جمهورية مصر العربية</span>
                        <h2 class="text-sm md:text-base font-bold text-primary font-serif leading-tight">جامعة مدينة السادات</h2>
                        <span class="text-[10px] md:text-[11px] text-gray-500">University of Sadat City</span>
                    </div>
                </div>

            </div>

        </div>
    </header>

    <!-- ========================================== -->
    <!-- 3. STICKY GLASSMORRHIC NAVBAR              -->
    <!-- ========================================== -->
    <nav class="glass-navbar sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center">
                
                <!-- Desktop Nav Links -->
                <ul class="hidden md:flex items-center gap-2 lg:gap-6 py-3.5 text-white font-bold text-base">
                    <li>
                        <a href="index.php" class="px-3 py-2 rounded-lg bg-accent/20 text-accent border border-accent/40 transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-home text-sm"></i>
                            <span>الرئيسية</span>
                        </a>
                    </li>
                    <li>
                        <a href="#documented-crafts" class="px-3 py-2 rounded-lg text-gray-200 hover:text-accent hover:bg-white/5 transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-scroll text-sm text-accent/80"></i>
                            <span>الحرف الموثقة</span>
                        </a>
                    </li>
                    <li>
                        <a href="#crafts-directory" class="px-3 py-2 rounded-lg text-gray-200 hover:text-accent hover:bg-white/5 transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-book-open text-sm text-accent/80"></i>
                            <span>دليل حرف المنوفية</span>
                        </a>
                    </li>
                    <li>
                        <a href="#interactive-map" class="px-3 py-2 rounded-lg text-gray-200 hover:text-accent hover:bg-white/5 transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-map-marked-alt text-sm text-accent/80"></i>
                            <span>الخريطة التفاعلية</span>
                        </a>
                    </li>
                    <li>
                        <a href="#artisans-stories" class="px-3 py-2 rounded-lg text-gray-200 hover:text-accent hover:bg-white/5 transition-colors duration-300 flex items-center gap-2">
                            <i class="fas fa-users text-sm text-accent/80"></i>
                            <span>قصص الحرفيين</span>
                        </a>
                    </li>
                </ul>

                <!-- Mobile Header Title & Toggle Button -->
                <div class="md:hidden flex items-center justify-between w-full py-3">
                    <span class="text-accent font-bold font-serif text-lg">بوابة التراث المنوفي</span>
                    <button id="navToggleBtn" class="text-white hover:text-accent p-2 rounded-lg bg-primary-light/50 border border-white/20 focus:outline-none" aria-label="القائمة">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

            </div>

            <!-- Mobile Dropdown Menu -->
            <div id="mobileNavMenu" class="hidden md:hidden pb-4 pt-2 border-t border-white/10">
                <ul class="flex flex-col gap-2 text-white font-bold text-base">
                    <li><a href="index.php" class="block px-3 py-2 rounded-lg bg-accent/20 text-accent">الرئيسية</a></li>
                    <li><a href="#documented-crafts" class="block px-3 py-2 rounded-lg hover:bg-white/10 hover:text-accent">الحرف الموثقة</a></li>
                    <li><a href="#crafts-directory" class="block px-3 py-2 rounded-lg hover:bg-white/10 hover:text-accent">دليل حرف المنوفية</a></li>
                    <li><a href="#interactive-map" class="block px-3 py-2 rounded-lg hover:bg-white/10 hover:text-accent">الخريطة التفاعلية</a></li>
                    <li><a href="#artisans-stories" class="block px-3 py-2 rounded-lg hover:bg-white/10 hover:text-accent">قصص الحرفيين</a></li>
                </ul>
            </div>

        </div>
    </nav>
