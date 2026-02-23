$(document).ready(function(){

  var windowWidth;

  // Scenes
  var controller;

  getOffsets();
  updateScenes();

  $(window).resize(function() {
      var newWindowWidth = $(window).width();
      if(newWindowWidth != windowWidth) {
          getOffsets();
          updateScenes();
          windowWidth = newWindowWidth;
      }
  });

  function getOffsets() {
      windowWidth   = $(window).width();
  }

  function updateScenes() {

  }

  var logoSwiper = new Swiper(".swiper-media-logos", {
    slidesPerView: 'auto',
    centeredSlides: true,
    loop: false,
    allowTouchMove: true,
    allowSlideNext: true,
    allowSlidePrev: true,
    speed: 320,
    spaceBetween: 0,
    breakpoints: {
      768: {
        centeredSlides: false,
        allowTouchMove: false,
        allowSlideNext: false,
        allowSlidePrev: false,
      },
    }
  });

  var quoteSwiper = new Swiper(".swiper-media-quotes", {
    slidesPerView: 1,
    centeredSlides: false,
    effect: 'fade',
    allowTouchMove: false,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    speed: 320,
    fadeEffect: { crossFade: true },
    spaceBetween: 0,
  });

  quoteSwiper.on('slideChange', function () {
      var index = this.realIndex;
      logoSwiper.slideTo(index); 

      $('.swiper-media-logos .swiper-slide').removeClass('swiper-slide-active');
      $('.swiper-media-logos .swiper-slide:eq('+index+')').addClass('swiper-slide-active');
  });

  $('.swiper-media-logos .swiper-slide').click(function(e) {
      gotoSlide(this);
  });

  function gotoSlide(element) {
      var $slide = $(element);
      var index = $slide.index();
      console.log(index);
      quoteSwiper.slideTo(index);
  };
  

});