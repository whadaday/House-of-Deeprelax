$(document).ready(function(){

    var windowWidth;

    getOffsets();

    $(window).resize(function() {
        getOffsets();
    });

    function getOffsets() {
        windowWidth   = $(window).width();
    } 
    
    $(".section-quote-logos").each(function() {
        var $quote = $('.animate-quote');
        var $logos = $('.animate-logos');
        animateContent($quote); 
        animateContent($logos);  
    });

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
                $element.removeClass('show');
            } else {
                $element.addClass('show');
            }
            
        })
        // .addIndicators()
        .addTo(controller);

    }


});