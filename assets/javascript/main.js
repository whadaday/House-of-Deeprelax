// @codekit-prepend '../../node_modules/jquery/dist/jquery.js'
// @codekit-prepend '../../node_modules/gsap/dist/gsap.js'
// @codekit-prepend '../../node_modules/scrollmagic/scrollmagic/uncompressed/ScrollMagic.js'
// @codekit-prepend '../../node_modules/scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap.js'
// @codekit-prepend '../../node_modules/scrollmagic/scrollmagic/uncompressed/plugins/debug.addindicators.js'
// @codekit-prepend '../../node_modules/scrollmagic/scrollmagic/uncompressed/plugins/jquery.ScrollMagic.js'
// @codekit-prepend '../../node_modules/headroom.js/dist/headroom.min.js'
// @codekit-prepend '../../node_modules/headroom.js/dist/jQuery.headroom.min.js'
// @codekit-prepend '../../node_modules/@fancyapps/ui/dist/fancybox/fancybox.umd.js
// @codekit-prepend '../../node_modules/swiper/swiper-bundle.js'
// @codekit-prepend '../../../../plugins/beam-popup/beam-popup.js'

// @codekit-prepend 'navigation.js'

jQuery(function ($) {

	var $body = $('body');
    var $nav  = $('#nav-bar');
    var $header = $('#header'); 

    var bodyStyles = window.getComputedStyle(document.body);  
 
    var windowWidth;
    var windowHeight;
    var navHeight;
    var headerHeight;

    // Scenes
    var controller;

    // Initial functions
    getOffsets();
    updateScenes();
    lazyLoad();

    Fancybox.bind('[data-fancybox]', {
        Thumbs: false,
        backdropClick: true,
        dragToClose: false,
        wheel: false,
        compact: false,
        hideScrollbar:true,
        Toolbar: {
            display: {
                left: [],
                middle: ["close"],
                right: [],
            },
        },
    }); 

    $(window).resize(function() {
        var newWindowWidth = $(window).width();
        if(newWindowWidth != windowWidth) {
            getOffsets();
            updateScenes();
            windowWidth = newWindowWidth;
        }
        windowHeight = $(window).outerHeight();
    });

    function getOffsets() {
        windowWidth  = $(window).width();
        windowHeight = $(window).outerHeight();
        navHeight    = $nav.outerHeight();
        headerHeight = $header.outerHeight();
    } 

    function updateScenes() {

        if ( typeof controller !== 'undefined') {controller.destroy(true);}
        controller  = new ScrollMagic.Controller();

        $(".content-animate").each(function() {
            animateContent(this); 
        });

        if ($("#progress").length) {
             animateProgressBar(this);
        }

        if ($("#nav-bottombanner").length) {
            animateBottomBanner();
        }

        // $('.parralax-holder').each(function() {
        //   parralaxImage(this);
        // });

        // $('.section-image-parralax').each(function() {
        //   parralaxImageOverlay(this);
        // });

        setCardBtnDummyWidth();
    }
 

     function setCardBtnDummyWidth() {

        $('.card-btn-dummy').each(function(index, element){
            var $this = $(this);
            var width = $this.find('span').outerWidth();
            $this.get(0).style.setProperty('--card-width', width+'px');
        });
    }

    $('.blog-carousel-featured').each(function(index, element){
        var $this = $(this);
        // TO FIX - responsiveness 
        var swiperMagazine = new Swiper(this, {
            cssMode: true,
            touchStartPreventDefault: true,
            slidesPerView: "auto",
            centeredSlides: true,
            spaceBetween: 16,
            loop: true,
            spaceBetween: 0,
            allowTouchMove: true,
            grabCursor: true,
            mousewheel: {
                forceToAxis: true,
                releaseOnEdges: true,
            },
            freeMode: false,
            arrows: true,
            navigation: {
              nextEl: ".arrow-next",
              prevEl: ".arrow-prev",
            },
            breakpoints: {
                768: {

                },
            },
            on: {
                    slideChange: function () {
                    var index = this.realIndex;
                    var $target = $this.find(".slide-content:eq("+index+")");
                    $this.find(".slide-content").removeClass('active');
                    $target.addClass('active');
                },
            }
        });
    });

    $(".carousel-blog").each(function(index, element){
        var $this = $(this);
        var swiper = new Swiper(this, {
            cssMode: true,
            touchStartPreventDefault: true,
            slidesPerView: 'auto',
            loop: false,
            grabCursor: true,
            spaceBetween: 16,
            mousewheel: false,
            freeMode: false,
            mousewheel: {
                forceToAxis: true,
                releaseOnEdges: true,
            },
            pagination: {
                el: ".swiper-pagination",
                type: "progressbar",
            },
            navigation: {
                nextEl: ".btn-slide-blog-next",
                prevEl: ".btn-slide-blog-prev",
            },
            breakpoints: {
                768: {
                  slidesPerView: 2,
                },
                1280: {
                  slidesPerView: 3,
                },
                1680: {
                  slidesPerView: 4,
                },
                2560: {
                  slidesPerView: 5,
                },
            },
        });
    });

    function animateProgressBar() {
        var $article = $('.section-article');
        var articleHeight = $article.find('.column-article').outerHeight();

        var offset = 0;

        var tween = TweenMax.to('#progress', 1, {ease:Linear.easeNone, width:'100%'});

        new ScrollMagic.Scene({
            triggerElement: $article,
            triggerHook: 0,
            offset: offset,
            duration: articleHeight-windowHeight
        })
        .on("start", function (event) {
            if(event.scrollDirection === 'FORWARD') {
                $('#progress').addClass('bar-show');
            }
        })
        .on("end", function (event) {
            if(event.scrollDirection === 'FORWARD') {
                $('#progress').removeClass('bar-show');
            }
            else {
                $('#progress').addClass('bar-show');
            }
            
        })
        // .addIndicators()
        .setTween(tween)
        .addTo(controller);
    }

    function animateBottomBanner() {
        var $bottomBanner = $('#nav-bottombanner');
        var $footer = $('#footer');
        var $header = $('#header');
        var headerHeight = $header.outerHeight();
        var $navShowAfterHeader = $("#nav-bottombanner[data-show-after-header='1'");

        if ($navShowAfterHeader.length && $header.length) {
            new ScrollMagic.Scene({
                triggerElement: $header,
                triggerHook: 0,
                offset: headerHeight,
                duration: 0
            })
            .on("start", function (event) {
                if(event.scrollDirection === 'FORWARD') {
                   $bottomBanner.addClass('show');
                } else {
                    $bottomBanner.removeClass('show');
                }
            })
            // .addIndicators()
            .addTo(controller);
        }

        new ScrollMagic.Scene({
            triggerElement: $footer,
            triggerHook: 1,
            offset: 0,
            duration: 0
        })
        .on("start", function (event) {
            if(event.scrollDirection === 'FORWARD') {
               $bottomBanner.addClass('hide');
            } else {
                $bottomBanner.removeClass('hide');
            }
        })
        // .addIndicators()
        .addTo(controller);
    }

    function animateContent(element) {
        
        var $element = $(element);

        new ScrollMagic.Scene({
            triggerElement: element,
            triggerHook: 0.95,
            offset: 0,
            duration: '30%'
        })
        .on("start", function (event) {
            if(event.scrollDirection == 'FORWARD') {
                $element.addClass('show');
            } else if(event.scrollDirection == 'REVERSE') {
                // $element.removeClass('show');
            } else {
                $element.addClass('show');
            }
            
        })
        // .addIndicators()
        .addTo(controller);

    }

    function parralaxImage(element) {
      var $image  = $(element);

      var triggerHook = 0.25;
      if(windowWidth < 768) {
        triggerHook = 0.5;
      }

      var tl = new TimelineMax();
          tl.to($image, 1, { yPercent: -10, z: 0, ease: Linear.easeNone });

      // PARRALAX IMAGE
      new ScrollMagic.Scene({
          triggerElement: $image,
          triggerHook: triggerHook,
          offset: 0,
          duration: '75%'
      })
      // .addIndicators()
      .setTween(tl) 
      .addTo(controller); 
    }

    $("input").focus(function() {
        $(this).parents('.wpforms-field:not(.wpforms-field-radio):not(.wpforms-field-name)').addClass('active');
        $(this).parents('.wpforms-field.name-simple').addClass('active');
        $(this).parents('.wpforms-field-row-block').addClass('active');
    });
    $("input").focusout(function() {
        $(this).parents('.wpforms-field:not(.wpforms-field-radio):not(.wpforms-field-name)').removeClass('active');
        $(this).parents('.wpforms-field.name-simple').removeClass('active');
        $(this).parents('.wpforms-field-row-block').removeClass('active');
    });
    $("input").change(function() {
        if($(this).val() != '') {
            $(this).parents('.wpforms-field:not(.wpforms-field-radio):not(.wpforms-field-name)').addClass('filled');
            $(this).parents('.wpforms-field.name-simple').addClass('filled');
            $(this).parents('.wpforms-field-row-block').addClass('filled');
        } else {
            $(this).parents('.wpforms-field:not(.wpforms-field-radio):not(.wpforms-field-name)').removeClass('filled');
            $(this).parents('.wpforms-field.name-simple').removeClass('filled');
            $(this).parents('.wpforms-field-row-block').removeClass('filled');
        }
    });
    $("textarea").focus(function() {
        $(this).parents('.wpforms-field').addClass('active');
    });
    $("textarea").focusout(function() {
        $(this).parents('.wpforms-field').removeClass('active');
    });
    $("textarea").change(function() {
        if($(this).val() != '') {
            $(this).parents('.wpforms-field').addClass('filled');
        } else {
            $(this).parents('.wpforms-field').removeClass('filled');
        }
    });

    $('#footer .nav-dropdown').click(function(e) {
        if(windowWidth < 768) {
            var $this = $(this);
            if(!$this.hasClass('active')) {
                $this.addClass('active');
                // var newScrollPosition = ($this.offset().top);
                // $('html, body').animate({
                //     scrollTop: newScrollPosition-navHeight
                // },500);
            } else {
                $this.removeClass('active');
            }
        }
    });

    function parralaxImageOverlay(element) {
      var $this  = $(element);
      var $image = $this;

      var tl = new TimelineMax();
          tl.to($image, 1, { yPercent: -150, z: 0, ease: Linear.easeNone });

      // PARRALAX IMAGE
      new ScrollMagic.Scene({
          triggerElement: $this,
          triggerHook: 0.75,
          offset: 0,
          duration: '100%'
      })
      // .addIndicators()
      .setTween(tl)
      .addTo(controller);
    }

    $('a').click(function(e) {
        
        var $this       = $(this);
        var href        = $this.attr('href');
        if(typeof href == 'undefined') {
            $this.attr('href','#');
            href        = $this.attr('href');
        }
        
        var firstChar   = href.charAt(0);

        if(href.substring(0, 10) == '#training-') {
            e.preventDefault();
            var strArray = href.split('-');
            var activeTraining = strArray[1];
            
            $('.training-wrapper').removeClass('active');
            $('#training-'+activeTraining).addClass('active');

        }

        //if hashtag
        if(firstChar == '#') {

            e.preventDefault();
        
            //return false of scrollen
            if (href != '#' && href.substring(0, 7) != '#popup-') {

                var newScrollPosition = ($(href).offset().top);

                if($body.hasClass("menu-open")) {
                    var lastScroll = $body.attr('data-scroll');
                    $body.removeClass('menu-open');
                    $html.removeClass('scroll-disabled');
                };

                if(navHeight) {
                    newScrollPosition = newScrollPosition-navHeight;
                }

                if($(href).length){
                    $('html, body').animate({
                        scrollTop: newScrollPosition+1
                    },500);
                }
                return;
            }
            return;
        }

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

});