$(document).ready(function(){

    var windowWidth;

    getOffsets();

    $(window).resize(function() {
        getOffsets();
    });

    function getOffsets() {
        windowWidth   = $(window).width();
    } 

    $(".section-logos").each(function(index, element){

        var $this = $(this);
        var $gallery = $this.find('.carousel-logos');

        if($gallery.length) {
            var carousel = new Swiper($gallery[0], {

                spaceBetween: 0,
                centeredSlides: true,
                speed: 3000,
                observer: true,
                allowTouchMove: false,
                observeParents: true,
                autoplay: {
                    delay: 0,
                    reverseDirection: false 
                },
                breakpoints: {
                    768: {
                        allowTouchMove: false,
                        speed: 5000,
                        spaceBetween: 104,
                    }
                },
                loop: true,
                spaceBetween: 16,
                slidesPerView: 'auto',
                disableOnInteraction: false
            });
        }
    });

});