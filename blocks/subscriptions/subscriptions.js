$(document).ready(function(){

	var $nav = $('#nav-bar');

	var windowWidth;
    var windowHeight;
    var navHeight;

    /* TO FIX: multiple swipers on the same page */
    var init = false;
    var swiperSubscriptions;

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
        windowHeight  = $(window).outerHeight();
        navHeight     = $nav.outerHeight();
    }

    function updateScenes() {

    	initCarousel();
    }

    function initCarousel() {

    	var activeSlide = $('.swiper-subscriptions').data('slide-active');

	    swiperSubscriptions = new Swiper(".swiper-subscriptions", {
	        slidesPerView: 1,
	        centeredSlides: true,
	        allowTouchMove: false,
	        speed: 0,
	        autoHeight: true,
	        initialSlide: activeSlide,
	        spaceBetween: 0,
	        
			breakpoints: {
				768: {
				  	slideToClickedSlide: false,
				},
			}
	    });
	    swiperSubscriptions.on('slideChange', function (swiper) {
	    	$('.btn-renewals .btn').removeClass('active');
	    	var index = swiper.activeIndex+1;
	    	$('.btn-renewals .btn:nth-child('+index+')').addClass('active');
		});

	    $('.btn-renewals .btn').click(function () {
			var $this = $(this);
			$('.btn-renewals .btn').removeClass('active');
			$this.addClass('active');
			var show = $this.data('show');
			swiperSubscriptions.slideTo(show);

			// if(windowWidth < 768) {

		    // 	var newScrollPosition = ($('.swiper-subscriptions').offset().top);

			//     $('html, body').animate({
		    //         scrollTop: newScrollPosition
		    //     },250);

			// }
		});

	}

});