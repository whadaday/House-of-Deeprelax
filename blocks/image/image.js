$(document).ready(function(){

    var bodyStyles = window.getComputedStyle(document.body);
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

        if ( typeof controller !== 'undefined') {controller.destroy(true);}
        controller  = new ScrollMagic.Controller();

        $('.img-sticky').each(function() {
          stickyImage(this);
        });
        $('.text-parralax-holder').each(function() {
          textParralaxImage(this);
        });
    }

    function textParralaxImage(element) {
      var $image  = $(element);
      var triggerHook = 0.25;
      if(windowWidth < 768) {
        triggerHook = 0.5;
      }

      var tl = new TimelineMax();
          tl.to($image, 1, { yPercent: -20, z: 0, ease: Linear.easeNone });

      // PARRALAX IMAGE
      new ScrollMagic.Scene({
          triggerElement: $image,
          triggerHook: triggerHook,
          offset: 0,
          duration: '75%'
      })
      .on("start", function (event) {
        if(event.scrollDirection == 'FORWARD') {
            $image.addClass('show');
        } else if(event.scrollDirection == 'REVERSE') {
            $image.removeClass('show');
        } else {
            $image.addClass('show');
        }

        })
      .setTween(tl)
      .addTo(controller);
    }

    function stickyImage(element) {

        var $image   = $(element);
        var $link    = $image.next('.link-holder');
        var $section = $image.parents('.section');
        var $dots    = $section.find('.dots');

        var name = $image.attr('id');
        var imageHeight = $image.outerHeight();

        if(windowWidth > 767) {

            $section.removeClass('sticky');
            $link.css('height', 'auto');
            $image.css('height', 'auto');
            $section.next().css('margin-top', 0);

            var navHeight = parseFloat(bodyStyles.getPropertyValue('--nav-height'));

            var $parralax = $image.find('.sticky-parralax-holder');

            var tl = new TimelineMax();
                tl.to($parralax, 1, { yPercent: -50, ease: Linear.easeNone });

            // PARRALAX IMAGE
            new ScrollMagic.Scene({
              triggerElement: $image,
              triggerHook: 0,
              offset: -navHeight,
              duration: imageHeight
            })
            // .addIndicators()
            .setTween(tl)
            .setPin('#'+name, {pushFollowers: false})
            .addTo(controller);
        } else {
            $section.addClass('sticky');
            $link.css('height', imageHeight+'px');
            $image.css('height', 2*imageHeight+'px');
            $section.next().css('margin-top', -1*imageHeight+'px');
            if($dots.length) {
                $dots.css('height', imageHeight+'px');
            }
        }
    }

});