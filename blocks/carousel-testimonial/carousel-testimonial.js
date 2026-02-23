$(document).ready(function(){

    var windowWidth;

    getOffsets();

    $(window).resize(function() {
        getOffsets();
    });

    function getOffsets() {
        windowWidth   = $(window).width();
    } 

    $(".section-carousel-testimonial").each(function(index, element){

        var $this = $(this);
        var $testimonials = $this.find('.carousel-testimonials');

        var factSwiper = new Swiper($testimonials[0], {
            slidesPerView: 1,
          loop: true,
          spaceBetween: 0,
          freeMode: false, 
          autoHeight: true,
          autoplay: false,
          pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
          navigation: {
              nextEl: ".btn-slide-testimonial-next",
              prevEl: ".btn-slide-testimonial-prev",
          },
          breakpoints: {
                768: {
                    grabCursor: false,
                    autoHeight: false,
                      allowTouchMove: false,
                      mousewheel: false,
                      speed: 1000,
                      slidesPerView: 'auto',
                },
            },
          on: {
              afterInit: function () {
                  var $this = $(this.$el);
                  $this.addClass('show');
              },
          }
        });
    });

    

});