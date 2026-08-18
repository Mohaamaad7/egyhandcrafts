<?php
/**
 * Footer Template - مشروع توثيق الحرف التراثية بمحافظة المنوفية
 * كلية السياحة والفنادق - جامعة مدينة السادات
 */
?>
<!-- ========================================== -->
<!-- FOOTER SECTION                             -->
<!-- ========================================== -->
<footer class="bg-primary border-t-4 border-accent py-8 mt-auto shadow-inner text-white relative z-30">
    <div class="container mx-auto px-4 lg:px-8 text-center">

        <!-- Institutional Ownership Notice (Styled Bold) -->

        <!-- Copyright and Year -->
        <p class="text-gray-300 text-sm mt-3 font-sans">
            &copy; <?php echo date('Y'); ?> جميع الحقوق محفوظة لجامعة مدينة السادات - كلية السياحة والفنادق| مشروع توثيق
            الحرف التراثية بمحافظة المنوفية
        </p>

    </div>
</footer>

<!-- AOS (Animate On Scroll) JavaScript Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    // Initialize AOS animations
    document.addEventListener('DOMContentLoaded', function () {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
        });

        // Mobile Navigation Toggle
        const navToggleBtn = document.getElementById('navToggleBtn');
        const mobileNavMenu = document.getElementById('mobileNavMenu');

        if (navToggleBtn && mobileNavMenu) {
            navToggleBtn.addEventListener('click', function () {
                mobileNavMenu.classList.toggle('hidden');
            });
        }
    });
</script>
</body>

</html>