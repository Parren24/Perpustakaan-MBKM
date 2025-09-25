<div class="scroll-to-top">
    <a href="#" class="scroll-to-top-btn">
        <i class="fa-solid fa-chevron-up"></i>
    </a>
</div>

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const scrollToTop = document.querySelector(".scroll-to-top");
            const scrollToTopBtn = document.querySelector(".scroll-to-top-btn");
            scrollToTopBtn.addEventListener("click", function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });

            // If content is scrolled down, show the button
            window.addEventListener("scroll", function() {
                if (window.scrollY > 300) {
                    scrollToTop.classList.add("visible");
                } else {
                    scrollToTop.classList.remove("visible");
                }
            });
        });
    </script>
@endpush
