$(document).ready(function(){

    var windowWidth;

    getOffsets();

    $(window).resize(function() {
        getOffsets();
        $('.section-carousel-cards').each(function(){
            setSubtitleHeights($(this).find('.carousel-cards'));
        });
    });

    function getOffsets() {
        windowWidth = $(window).width();
    } 

    function setSubtitleHeights($carousel) {
        var $subtitles = $carousel.find('.card-subtitle');
        $subtitles.css('height', 'auto');

        var maxHeight = 0;
        $subtitles.each(function(){
            var h = $(this).outerHeight();
            if (h > maxHeight) {
                maxHeight = h;
            }
        });

        if (maxHeight > 0) {
            $subtitles.height(maxHeight);
        }
    }

    // 🔹 Helper om slides te filteren op basis van type
    function filterSlides(swiper, type) {
        for (var i = 0; i < swiper.slides.length; i++) {
            var slide     = swiper.slides[i];
            var $slide    = $(slide);
            var slideType = $slide.data('type');

            if (!type || type === slideType) {
                $slide.removeClass('is-hidden');
            } else {
                $slide.addClass('is-hidden');
            }
        }

        swiper.update();
        swiper.slideTo(0);
    }

    $(".section-carousel-cards").each(function(index, element){

        var $section  = $(this);
        var $carousel = $section.find('.carousel-cards');

        var swiper = new Swiper($carousel[0], {
            slidesPerView: 'auto',
            loop: false,
            grabCursor: true,
            spaceBetween: 16,
            mousewheel: false,
            freeMode: false,
            mousewheel: {
                forceToAxis: true,
                releaseOnEdges: true,
                thresholdDelta: 30,
                thresholdTime: 500
            },
            navigation: {
                nextEl: ".btn-slide-cards-next",
                prevEl: ".btn-slide-cards-prev",
            },
            breakpoints: {
                768: {
                    cssMode: false,
                  slidesPerView: 2,
                  grabCursor: false,
                  allowTouchMove: false,
                  mousewheel: false,
                },
                992: {
                    cssMode: false,
                  slidesPerView: 3,
                  grabCursor: false,
                  allowTouchMove: false,
                  mousewheel: false,
                },
                1280: {
                    cssMode: false,
                  slidesPerView: 4,
                  grabCursor: false,
                  allowTouchMove: false,
                  mousewheel: false,
                },
            },
            on: {
                afterInit: function () {
                    var $this = $(this.$el);
                    setSubtitleHeights($this);
                    $this.addClass('show');
                },
            }
        });

        // Swiper instance bewaren
        $section.data('swiper', swiper);

        // 🔹 INIT: filter toepassen op de al actieve category
        var $activeFilter = $section.find('.list-filters a.active').first();

        // Fallback: als er nog geen active is, pak de eerste en maak die active
        if (!$activeFilter.length) {
            $activeFilter = $section.find('.list-filters a').first().addClass('active');
        }

        if ($activeFilter.length) {
            var initialType = $activeFilter.data('type');
            filterSlides(swiper, initialType);
        }
    });

    // Klikken op filters
    $('.section-carousel-cards').on('click', '.list-filters a', function(e) {
        e.preventDefault();

        var $link    = $(this);
        var $section = $link.closest('.section-carousel-cards');
        var swiper   = $section.data('swiper');

        $link.closest('.list-filters').find('a').removeClass('active');
        $link.addClass('active');

        var type = $link.data('type');

        filterSlides(swiper, type);
    });

    const row = document.querySelector('.list-filters');

    if (row) {
      row.addEventListener('click', (e) => {
        const btn = e.target.closest('a');
        if (!btn) return;

        // Alleen op mobile uitvoeren
        const isMobile = window.matchMedia('(max-width: 768px)').matches;
        if (!isMobile) return;

        btn.scrollIntoView({
          behavior: 'smooth',
          block: 'nearest',
          inline: 'center'
        });
      });
    }

});
