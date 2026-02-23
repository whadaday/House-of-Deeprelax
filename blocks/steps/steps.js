$(document).ready(function(){

    var windowWidth;
    var windowHeight;

    // Scenes
    var controller;

    /* TO FIX: multiple section steps on the same page */

    var init = false;
    var contentSwiper;
    var imageSwiper;

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
    }

    function updateScenes() {

        if ( typeof controller !== 'undefined') {controller.destroy(true);}
        controller  = new ScrollMagic.Controller();

        $('.section-steps').each(function() {
            animateStepsCarousel(this);
        });
    }

    function animateStepsCarousel(element) {


        var $galleryContentHolder = $(element).find('.steps-text-holder');
        var $galleryImagesHolder = $(element).find('.steps-images-holder');
        var $galleryPaginationHolder = $(element).find('.steps-pagination');

        var $carouselVertical = $galleryImagesHolder.parent('.swiper');
        var carouselVerticalID = $carouselVertical.attr('id');

        var galleryImagesHolderID = $galleryImagesHolder.attr('id');
        var galleryContentHolderID = $galleryContentHolder.attr('id');

        if(windowWidth > 767) {

            if(init) {
                imageSwiper.destroy();
                contentSwiper.destroy();
                init = false;
            }

            var galleryHeight = $(element).height();
            var imageHeight = $galleryImagesHolder.parents('.column-image').outerHeight();

            var sectionPos = $(element).offset();
            var sectionPosY = sectionPos.top;

            var imagePos = $galleryImagesHolder.parents('.column-image').offset();
            var imagePosY = imagePos.top;
            
            var offset = ((windowHeight-imageHeight)/2)-(imagePosY-sectionPosY);

            new ScrollMagic.Scene({
                triggerElement: element,
                triggerHook: 0,
                offset: -offset,
                duration: galleryHeight-imageHeight-(imagePosY-sectionPosY)
            })
            .setPin('#'+galleryImagesHolderID, {pushFollowers: false})
            // .addIndicators()
            .addTo(controller);

            $(element).find('.content-step').each(function() {
                var $this = $(this);
                var id = $this.attr('id');
                var index = $this.index();
                var $image = $galleryImagesHolder.find('.step-image:nth-child('+(index+1)+')').find('.img-background');
                var tl = new TimelineMax();
                tl.to($image, 1, { yPercent: -5, ease: Linear.easeNone });

                new ScrollMagic.Scene({
                    triggerElement: this,
                    triggerHook: 0.5,
                    offset: 0, 
                    duration: '100%'
                })
                // .addIndicators()
                .setTween(tl)
                .addTo(controller);

                new ScrollMagic.Scene({
                    triggerElement: this,
                    triggerHook: 0.8,
                    offset: windowHeight/2,
                    duration: '50%' 
                })
                //.addIndicators()
                .on("start", function (event) {
                    if(index != 0) {
                        if(event.scrollDirection == 'FORWARD') {
                            var nextIndex = index+1;
                            $galleryImagesHolder.find('.step-image:nth-child('+nextIndex+')').addClass('active'); 
                        } else if(event.scrollDirection == 'REVERSE') {
                            var prevIndex = index+1;
                            $galleryImagesHolder.find('.step-image:nth-child('+prevIndex+')').removeClass('active'); 
                        }
                    }
                    $galleryContentHolder.find('.content-step').removeClass('active');
                    if(event.scrollDirection == 'FORWARD') {
                        var nextIndex = index+1;
                        $galleryContentHolder.find('.content-step:nth-child('+nextIndex+')').addClass('active'); 
                    } else if(event.scrollDirection == 'REVERSE') {
                        var prevIndex = index;
                        $galleryContentHolder.find('.content-step:nth-child('+prevIndex+')').addClass('active'); 
                    }

                    $galleryPaginationHolder.find('li').removeClass('active');
                    if(event.scrollDirection == 'FORWARD') {
                        var nextIndex = index+1;
                        $galleryPaginationHolder.find('li:nth-child('+nextIndex+')').addClass('active'); 
                    } else if(event.scrollDirection == 'REVERSE') {
                        var prevIndex = index;
                        if(prevIndex == 0) { prevIndex = 1; }
                        $galleryPaginationHolder.find('li:nth-child('+prevIndex+')').addClass('active'); 
                    }

                })
                .addTo(controller);
            });
        }
        else {

            if (!init) {
                init = true;

                imageSwiper = new Swiper('#'+carouselVerticalID, {
                    slidesPerView: 'auto',
                    loop: false,
                    grabCursor: true,
                    spaceBetween: 16,
                    mousewheel: false,
                    freeMode: false,
                });

                contentSwiper = new Swiper('#'+galleryContentHolderID, {
                    slidesPerView: 'auto',
                    loop: false,
                    grabCursor: true,
                    spaceBetween: 16,
                    mousewheel: false,
                    autoHeight: false,
                    freeMode: false,
                    pagination: {
                        el: '.swiper-pagination',
                        type: 'bullets',
                        clickable: true,
                    },
                });

                imageSwiper.controller.control = contentSwiper;
                contentSwiper.controller.control = imageSwiper;
            
            }
        }
    }

});