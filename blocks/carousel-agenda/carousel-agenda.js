$(document).ready(function(){

    var windowWidth;

    var $agendaNavigation = $('#agenda-navigation');

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

        if ( typeof controller !== 'undefined') {controller.destroy(true);}
        controller  = new ScrollMagic.Controller();

        if ($agendaNavigation.length) {
             animateAgendaNavigation();
        }

        setGalleryHeight();
        

    }

    function cssVar(name,value){
        if(name[0]!='-') name = '--'+name //allow passing with or without --
        if(value) document.documentElement.style.setProperty(name, value)
        return getComputedStyle(document.documentElement).getPropertyValue(name);
    }

    function setGalleryHeight() {
        var $gallery = $('.swiper-agenda');
        $gallery.css('height', 'auto');

        if(windowWidth > 767) {

            var $activeSlide = $gallery.find('.swiper-slide-active');
            var $slide = $gallery.find('.swiper-slide:not(.swiper-slide-active');
            
            setTimeout(() => {
                var height = $activeSlide.outerHeight();
                $gallery.css('height', height);
                cssVar('slide-width', $slide.outerWidth());
                cssVar('slide-active-width', $activeSlide.outerWidth());
            }, 240);
        }
    }

    function animateAgendaNavigation() {

        var $carousel = $('.section-carousel-agenda');
        var carouselName = $carousel.attr('id');
        var carouselHeight = $carousel.outerHeight();

        var agendaNavigationHeight = $agendaNavigation.outerHeight();

        new ScrollMagic.Scene({
            triggerElement: '#'+carouselName,
            triggerHook: 0,
            offset: -agendaNavigationHeight-1,
            duration: carouselHeight
        })
        .on("end", function (event) {
            if(event.scrollDirection == 'FORWARD') {
                $agendaNavigation.addClass('active');
            } else if(event.scrollDirection == 'REVERSE') {
                $agendaNavigation.removeClass('active');
            } else {
                $agendaNavigation.addClass('active');
            }
            
        })
        // .addIndicators()
        .addTo(controller);
    }

    $(".section-carousel-agenda").each(function(index, element){

        var $this = $(this);
        var $gallery = $this.find('.swiper-agenda');
        var activeSlide = $gallery.data('active-slide');
        var amountSlides = $gallery.data('amount-slides');

        var $agendaNav = $this.find('#agenda-navigation');
        if($agendaNav.length) {
            $agendaNav.appendTo("#wrapper");
        }

        // $btnNext = $this.find('.btn-slide-next');
        // $btnPrev = $this.find('.btn-slide-prev');

        // var carousel = new Swiper($gallery[0], {
        //     slidesPerView: "auto",
        //     spaceBetween: 0,
        //     loop: false,
        //     slideToClickedSlide: false,
        //     pagination: {
        //         el: ".swiper-pagination",
        //         clickable: true,
        //     },
        //     breakpoints: {
        //         768: {
        //             initialSlide: activeSlide-1,
        //             allowTouchMove: false,
        //             centeredSlides: true,
        //         },
        //     },
        //     on: {
        //         afterInit: function (swiper) {
        //             setTimeout(() => {
        //                 checkHashUrl(this);
        //                 carousel.updateSize();
        //                 setGalleryHeight();
        //             }, 640);
                    
        //         },
        //         slideChangeTransitionStart: function(swiper) {
        //             var activeIndex = swiper.activeIndex;
        //             var prevIndex   = swiper.previousIndex;
        //             var swiperWidth = swiper.width;

        //             if(prevIndex > activeIndex) {
        //                 var moving = prevIndex - activeIndex;
        //             } else {
        //                 var moving = activeIndex - prevIndex;
        //             }

        //             var slideWidth = cssVar('slide-width');
        //             var activeSlideWidth = cssVar('slide-active-width');

        //             var translate = ( (swiperWidth - activeSlideWidth) / 2) - (activeIndex * slideWidth);

        //             var speed = 240 * moving;
        //             var totalSlides = swiper.slides.length;

        //             swiper.translateTo(translate, speed);

        //             if(totalSlides-1 == activeIndex) {
        //                 $btnNext.addClass('swiper-button-disabled');
        //             } else {
        //                 $btnNext.removeClass('swiper-button-disabled');
        //             }
        //             if(activeIndex == 0) {
        //                 $btnPrev.addClass('swiper-button-disabled');
        //             } else {
        //                 $btnPrev.removeClass('swiper-button-disabled');
        //             }
        //         }
        //     }
        // });

        // $('.btn-slide-next').click(function(e) {
        //     carousel.slideTo(carousel.activeIndex+1);
        // });
        // $('.btn-slide-prev').click(function(e) {
        //     carousel.slideTo(carousel.activeIndex-1);
        // });

        // $('.section-carousel-agenda .content-image').click(function(e) {
        //     gotoSlide(this);
        // });
        // $('.section-carousel-agenda .content-title').click(function(e) {
        //     gotoSlide(this);
        // });

        // function gotoSlide(element) {
        //     var $slide = $(element).parents('.swiper-slide');
        //     if($slide.hasClass('swiper-slide-active')) { return; }
        //     var index = $slide.index();
        //     carousel.slideTo(index);
        // };

        var sliderLoop = false;
        var sliderRewind = true;

        if(amountSlides >= 5) {
            sliderLoop = true;
            sliderRewind = false;
        }

        var carousel = new Swiper($gallery[0], {
            slidesPerView: "auto",
            spaceBetween: 0,
            loop: true,
            rewind: false,
            slideToClickedSlide: true,
            autoHeight: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: '.btn-slide-next',
                prevEl: '.btn-slide-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    initialSlide: activeSlide-1,
                    allowTouchMove: false,
                    centeredSlides: true,
                    loop: sliderLoop,
                    rewind: sliderRewind,
                    autoHeight: false,
                },
                1280: {
                    slidesPerView: 3,
                    initialSlide: activeSlide-1,
                    allowTouchMove: false,
                    centeredSlides: true,
                    loop: sliderLoop,
                    rewind: sliderRewind,
                    autoHeight: false,
                },
                1680: {
                    slidesPerView: 5,
                    initialSlide: activeSlide-1,
                    allowTouchMove: false,
                    centeredSlides: true,
                    loop: sliderLoop,
                    rewind: sliderRewind,
                    autoHeight: false,
                },
            },
            on: {
                afterInit: function (swiper) {
                    setTimeout(() => {
                        checkHashUrl(this);
                        carousel.updateSize();
                        setGalleryHeight();
                        lazyLoad();
                    }, 640);
                    
                },
            }
        });
    });

    function lazyLoad() {
        /** First we get all the non-loaded image elements **/
        var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
        /** Then we set up a intersection observer watching over those images and whenever any of those becomes visible on the view then replace the placeholder image with actual one, remove the non-loaded class and then unobserve for that element **/
        let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;

                    var interval = setInterval(function () {
                        if (lazyImage.complete) {
                            clearInterval(interval);
                            lazyImage.classList.remove("lazy");
                            lazyImage.classList.add("loaded");
                            lazyImage.removeAttribute('data-src');
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    }, 320);

                }
            });
        });
        /** Now observe all the non-loaded images using the observer we have setup above **/
        lazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });



        /** First we get all the non-loaded image elements **/
        var lazyVideos = [].slice.call(document.querySelectorAll("video.lazy"));
        /** Then we set up a intersection observer watching over those images and whenever any of those becomes visible on the view then replace the placeholder image with actual one, remove the non-loaded class and then unobserve for that element **/
        let lazyVideoObserver = new IntersectionObserver(function(entries, observer) {   
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyVideo = entry.target;

                    for (var source in entry.target.children) {
                        var videoSource = entry.target.children[source];
                        if (typeof videoSource.tagName === "string" && videoSource.tagName === "SOURCE") {
                        videoSource.src = videoSource.dataset.src;
                        }
                    }

                    lazyVideo.load();

                    var interval = setInterval(function () {
                        if (lazyVideo.readyState === 4) {
                            clearInterval(interval);
                            lazyVideo.classList.remove("lazy");
                            lazyVideo.classList.add("loaded");
                            lazyVideoObserver.unobserve(lazyVideo);
                        }
                    }, 320);
                    
                }
            });
        });
        /** Now observe all the non-loaded videos using the observer we have setup above **/
        lazyVideos.forEach(function(lazyVideo) {
            lazyVideoObserver.observe(lazyVideo);
        });

    }

    function checkHashUrl(swiper) {
        if(window.location.hash) {
            var hash = window.location.hash;
            if(hash.substring(0, 10) == '#training-') {
                var strArray = hash.split('-');
                var activeTraining = strArray[1];

                swiper.slideTo(activeTraining);
                
                $('.training-wrapper').removeClass('active');
                $('#training-'+activeTraining).addClass('active');

                if($('.list-agenda').length) {
                    $('.list-agenda a').removeClass('active');
                    $('.list-agenda [href="#training-'+activeTraining+'"]').addClass('active');
                }

                var navAgendaHeight;
                var $navAgenda = $('#agenda-navigation');
                if($navAgenda.length) {
                    var navAgendaHeight = $navAgenda.outerHeight();
                }

                var newScrollPosition = ($(hash).offset().top);

                 $('html, body').animate({
                    scrollTop: newScrollPosition-navAgendaHeight
                },500);
            }
        }
    }

    $('a').click(function(e) {
        var $this       = $(this);
        var href        = $this.attr('href');

        var navAgendaHeight;
        var $navAgenda = $('#agenda-navigation');
        if($navAgenda.length) {
            var navAgendaHeight = $navAgenda.outerHeight();
        }

        if(href.substring(0, 10) == '#training-') {
            var strArray = href.split('-');
            var activeTraining = strArray[1];
            
            $('.training-wrapper').removeClass('active');
            $('#training-'+activeTraining).addClass('active');

            if($('.list-agenda').length) {
                $('.list-agenda a').removeClass('active');
                $('.list-agenda [href="#training-'+activeTraining+'"]').addClass('active');
            }
        }

    });


});