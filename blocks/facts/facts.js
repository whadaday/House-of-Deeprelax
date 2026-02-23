$(document).ready(function(){

    var windowWidth;

    getOffsets();

    $(window).resize(function() {
        getOffsets();
    });

    function getOffsets() {
        windowWidth   = $(window).width();
    } 

    $(".section-facts").each(function(index, element){

        var $this = $(this);
        var $facts = $this.find('.carousel-facts');
        var $images = $this.find('.carousel-facts-images');

        var factSwiper = new Swiper($facts[0], {
          slidesPerView: 1,
          effect: 'fade',
          fadeEffect: { crossFade: true },
          loop: true,
          grabCursor: true,
          spaceBetween: 0,
          grabCursor: false,
          allowTouchMove: true,
          mousewheel: false,
          freeMode: false,
          pagination: {
              el: ".swiper-pagination",
              clickable: true,
          },
          breakpoints: {
              768: {
                  allowTouchMove: false,
              },
          },
          navigation: {
              nextEl: ".btn-slide-fact-next",
              prevEl: ".btn-slide-fact-prev",
          },
          on: {
              afterInit: function () {
                  var $this = $(this.$el[0]);
                  $this.addClass('show');
              },
          }
        });

        var imageSwiper = new Swiper($images[0], {
          slidesPerView: 1,
          effect: 'fade',
          fadeEffect: { crossFade: true },
          loop: true,
          grabCursor: false,
          allowTouchMove: true,
          spaceBetween: 0,
          mousewheel: false,
          freeMode: false,
          breakpoints: {
              768: {
                  allowTouchMove: false,
              },
          },
          on: {
              afterInit: function () {
                  var $this = $(this.$el[0]);
                  $this.addClass('show');
              },
          }
        });

        imageSwiper.controller.control = factSwiper;
        factSwiper.controller.control = imageSwiper;
    });
    

});