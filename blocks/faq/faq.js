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

    $(window).on('load', function() {
        if(window.location.hash) {
            var faq_id =  window.location.hash.replace("#", "");
            var $target = $('.accordion__row[data-id='+faq_id+']');

            if($target.length) {
                $target.addClass('active');
                $('html, body').animate({
                    scrollTop: $target.offset().top
                },500);
            }
        }
    });

    function getOffsets() {
        windowWidth   = $(window).width();
    }

    function updateScenes() {

        if ( typeof controller !== 'undefined') {controller.destroy(true);}
        controller  = new ScrollMagic.Controller();

        $('.accordion__panel').each(function() {
          var $this = $(this);
          var height = $this.find('.accordion__panel__inner').outerHeight();
          $this.get(0).style.setProperty('--panel-height', height+'px');
        });

    }

    $('.btn-accordion').click(function() {
      var $this = $(this);
      var $container = $this.parents('.accordion__row');
      if($container.hasClass('active')) {
        $container.removeClass('active');
      } else {
        $this.parents('.accordion').find('.accordion__row').removeClass('active');
        $container.addClass('active');
      }
    });

});