jQuery(function ($) {

    var $html         = $('html');
	var $body         = $('body');
    var $announcement = $('#nav-announcement');
    var $nav          = $('#nav-bar');
    var $header       = $('#header'); 

    var bodyStyles = window.getComputedStyle(document.body);  
 
    var windowWidth;
    var windowHeight;
    var navHeight;
    var headerHeight;

    var scrollY;

    // Scenes
    var controller;

    // Initial functions
    getOffsets();
    updateScenes();
    animateNavHideScroll();

    function animateNavHideScroll() {
        if($nav.data('hide-scroll')) {
            var headroomOffset = 0;
            if($announcement.length) {
                headroomOffset = $announcement.outerHeight();
            }

            var headroomOptions = {
                offset: {
                    up: headroomOffset,
                    down: headroomOffset
                },
            };
            $nav.headroom(headroomOptions);
        }
    }

    $(window).resize(function() {
        var newWindowWidth = $(window).width();
        if(newWindowWidth != windowWidth) {
            getOffsets();
            updateScenes();
            windowWidth = newWindowWidth;
        }
        windowHeight = $(window).outerHeight();

        if(windowWidth > 767) {
            $html.removeClass('scroll-disabled');
            $body.removeClass('menu-open');
        }
    });

    function getOffsets() {
        windowWidth  = $(window).width();
        windowHeight = $(window).outerHeight();
        navHeight    = $nav.outerHeight();
        headerHeight = $header.outerHeight();

        setDropdownHeight();
    } 

    function updateScenes() {

        if ( typeof controller !== 'undefined') {controller.destroy(true);}
        controller  = new ScrollMagic.Controller();

        if($header.length && $nav.data('behind-header') && windowWidth > 767){
            animateNavHideHeader();
        }
    }

    $(document).keyup(function(e) {
         if (e.key === "Escape") { // escape key maps to keycode `27`
            closeMenuDropdown();
            closeMenuSidebar();
            resetCarouselNavCardsSwiper();
            $body.removeClass('menu-open');
            $html.removeClass('scroll-disabled');
        }
    });

    function closeMenuDropdown() {
        if(windowWidth < 768) {
            $('#nav-mobile').removeClass('dropdown-active');
            $('.nav-dropdown-content').removeClass('active');
            $('.nav-back').removeClass('active');

            // Reset overflow scroll container
            setTimeout(() => {
               $('.nav-mobile-container .nav-dropdown-content .container').scrollTop(0);
            }, 640);

        } else {
            if($('#nav-dropdown').length ) {
                $('.list-nav .menu-item-has-dropdown').removeClass('active');
                $('#nav-bar').removeClass('dropdown-active');
                $('.nav-dropdown-content').removeClass('active');
                $('#nav-dropdown').get(0).style.setProperty('--dropdown-height', 0+'px');
            }
        }
    }

    // Dropdown nav
    $('.list-nav .menu-item-has-dropdown > a').click(function(e) {
        var $this = $(this);
        e.preventDefault();

        if($this.parent('li').hasClass('active')) {
            closeMenuDropdown();
        } else {
            var page = $this.parent('li').data('item');
            var $target = $('.nav-dropdown-content[data-dropdown-item="'+page+'"');
        
            $('.list-nav .menu-item-has-dropdown').removeClass('active');
            $this.parent('li').addClass('active');
            $('#nav-bar').addClass('dropdown-active');
            $('.nav-dropdown-content').removeClass('active');
            $target.addClass('hide');
            setCardBtnDummyWidth();
            setTimeout(() => {
                $target.addClass('active');
                setDropdownHeight($target);
            }, 10);
            
        }
    });

    // Fallback dropdown menu
    $('.list-overlay .menu-item-has-children > a').click(function(e) {
        if(windowWidth < 768) {
            var $this = $(this);
            e.preventDefault();
            $this.parent('li').toggleClass('active');
        }
    });

    // Mobile nav
    $('#nav-mobile .list-nav-overlay .menu-item-has-dropdown > a').click(function(e) {
        var $this = $(this);
        e.preventDefault();

        var page = $this.parent('li').data('item');
        var $target = $('.nav-dropdown-content[data-dropdown-item="'+page+'"');
        $('#nav-mobile').addClass('dropdown-active');
        $('.nav-back').addClass('active');
        $target.addClass('hide');
        setCardBtnDummyWidth();
        setTimeout(() => {
            $target.addClass('active');
        }, 10);

        // Reset overflow scroll container
        setTimeout(() => {
           $('.nav-mobile-inner').scrollTop(0);
        }, 640);
    });

    // Fallback Mobile nav dropdown
    $('#nav-mobile .list-nav-overlay .menu-item-has-children > a').click(function(e) {
        var $this = $(this);
        e.preventDefault();
        $this.parent('li').toggleClass('active');
    });

    // Mobile nav back
    $('.nav-back').click(function(e) {
        var $this = $(this);
        e.preventDefault();
        closeMenuDropdown();
        resetCarouselNavCardsSwiper();
    });

    function setDropdownHeight($target) {
        var $navDropdown = $('#nav-dropdown');
        if($navDropdown.length) {
            if(typeof $target == 'undefined') {
                $target = $('.nav-dropdown-content.active');
            }
            var targetHeight = $target.outerHeight();
            if(typeof targetHeight == 'undefined') {
                targetHeight = 0;
            }
            $('#nav-dropdown').get(0).style.setProperty('--dropdown-height', targetHeight+'px');
        }
    }

    $(window).scroll(function(){
        if(windowWidth > 767) {
            closeMenuDropdown();
            $html.removeClass('scroll-disabled');
        } else {
            $body.get(0).style.setProperty('--scroll-y', `${window.scrollY}px`);
        }
    });

    $body.on('click', '#nav-backdrop', function(e) {     
        var $this = $(this);
        e.preventDefault();
        closeMenuDropdown();
        closeMenuSidebar();
        resetCarouselNavCardsSwiper();
        $body.removeClass('menu-open');
        $html.removeClass('scroll-disabled');
    });


    function closeMenuSidebar() {
        // Reset overflow scroll container
        setTimeout(() => {
           $('.nav-side-content').scrollTop(0);
        }, 240);
    }

    function animateNavHideHeader() {
        new ScrollMagic.Scene({
            triggerElement: 0,
            triggerHook: 0,
            offset: 0,
            duration: headerHeight
        })
        .on("progress", function (event) {
            // console.log(event.progress);
            if(event.progress > 0.5) {
                $nav.addClass('menu-hide');
                if(event.scrollDirection == 'FORWARD') {
                    setTimeout(() => {
                       $nav.addClass('menu-reveal');
                    }, 10);
                }
            }
            else {
                $nav.removeClass('menu-reveal');
                setTimeout(() => {
                   $nav.removeClass('menu-hide');
                }, 10);
            }
        })
        .on("end", function (event) {
            if(event.scrollDirection == 'FORWARD') {
                $nav.addClass('menu-show');
            } else {
                $nav.removeClass('menu-show');
            }
        }) 
        // .addIndicators()
        .addTo(controller);
    }

    $('.toggle-menu').click(function(e) {
        e.preventDefault();
       
        if(windowWidth > 767) {
            if($body.hasClass("menu-open")) {
                $body.removeClass('menu-open');
                closeMenuSidebar();
                resetCarouselNavCardsSwiper();
            } else {
                closeMenuDropdown();
                $body.addClass('menu-open');
            }
        } else {

            if($body.hasClass("menu-open")) {
                scrollY = $body.css('top');

                $body.removeClass('menu-open');
                resetCarouselNavCardsSwiper();
                closeMenuDropdown();
                
                $html.removeClass('scroll-disabled');
                $body.css('top', 0);
                $('#nav-main').css('transform', '');
                window.scrollTo(0, parseInt(scrollY || '0') * -1);
                

            } else {
                scrollY = parseFloat(bodyStyles.getPropertyValue('--scroll-y'));
                if(isNaN(scrollY)) {
                    scrollY = 0;
                }

                if($announcement.length) {
                    var announcementHeight = $announcement.outerHeight();
                    if(scrollY < announcementHeight) {
                        var offset = announcementHeight-scrollY;
                        $('#nav-main').css('transform', 'translateY(-' + offset + 'px)');
                    }
                }

                $body.addClass('menu-open');
                $html.addClass('scroll-disabled');
                $body.css('top', -scrollY); body.style.top = `-${scrollY}`;
            }
        }
      });


    var carouselNavSideCardsSwiper = [];
    $('#nav-side .carousel-nav-cards').each(function(index, element){
        var $this = $(this);
        carouselNavSideCardsSwiper[index] = new Swiper(this, {
            slidesPerView: 'auto',
            loop: false,
            grabCursor: true,
            spaceBetween: 12,
            mousewheel: false,
            freeMode: false,
            mousewheel: {
                forceToAxis: true,
                releaseOnEdges: true,
            },
        });
    });

    var carouselNavMobileCardsSwiper = [];
    $('#nav-mobile .carousel-nav-cards').each(function(index, element){
        var $this = $(this);
        carouselNavMobileCardsSwiper[index] = new Swiper(this, {
            slidesPerView: 'auto',
            loop: false,
            grabCursor: true,
            spaceBetween: 12,
            mousewheel: false,
            freeMode: false,
            mousewheel: {
                forceToAxis: true,
                releaseOnEdges: true,
            },
        });
    });

    function resetCarouselNavCardsSwiper() {
        setTimeout(() => {
            $.each( carouselNavSideCardsSwiper, function( index ) {
              carouselNavSideCardsSwiper[index].slideTo(0,0);
            });
            $.each( carouselNavMobileCardsSwiper, function( index ) {
              carouselNavMobileCardsSwiper[index].slideTo(0,0);
            });
        }, 640);
    }

    function setCardBtnDummyWidth() {

        $('.card-btn-dummy').each(function(index, element){
            var $this = $(this);
            var width = $this.find('span').outerWidth();
            $this.get(0).style.setProperty('--card-width', width+'px');
        });
    }
});