$(function () {
    let isAnimating = false; // Flag animasi sedang berjalan

    // SMOOTH SCROLL - Lebih cepat & halus
    $('a[href^="#"]').on('click', function (e) {
        const hrefValue = $(this).attr('href');

        // Abaikan jika hanya '#' atau elemen Bootstrap toggle
        if (hrefValue === '#' || $(this).is('[data-toggle]')) return;

        const target = $(hrefValue);
        if (target.length) {
            e.preventDefault();
            isAnimating = true; // Mulai animasi

            $('html, body').stop().animate({
                scrollTop: target.offset().top - 70
            }, 500, 'swing', function () {
                isAnimating = false; //  Animasi selesai
                $(window).trigger('scroll'); // Paksa update posisi setelah animasi
            });
        }
    });

        // NAVIGASI SAAT SCROLL
        const sections = $('.content-section');
        const topNavLinks = $('.top-navbar .nav-item');
        const sideNavLinks = $('.sidebar .nav-link');

        $(window).on('scroll', function () {
            if (isAnimating) return; // Jangan update saat sedang animasi

            const scrollTop = $(this).scrollTop() + 80;
            let currentId = null;

            sections.each(function () {
                if ($(this).offset().top <= scrollTop) {
                    currentId = this.id;
                }
            });

            if (currentId) {
                // Update top navbar
                topNavLinks.removeClass('active');
                topNavLinks.find(`a[href="#${currentId}"]`).closest('.nav-item').addClass('active');

                // Update sidebar
                sideNavLinks.removeClass('active-sidebar-link');
                sideNavLinks.filter(`[href="#${currentId}"]`).addClass('active-sidebar-link');

                if (currentId === 'about-us') {
                    sideNavLinks.filter('[href="#about-us"]:contains("Home")').addClass('active-sidebar-link');
                }
            } else if (scrollTop < 50) {
                topNavLinks.removeClass('active');
                topNavLinks.first().addClass('active');

                sideNavLinks.removeClass('active-sidebar-link');
                sideNavLinks.filter('[href="#about-us"]:contains("Home")').addClass('active-sidebar-link');
            }
        }).trigger('scroll');
    });
