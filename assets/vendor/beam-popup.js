$(document).ready(function(){
    
    var $body         = $('body');
    var $popupTimer   = $('.popup-overlay[data-timer]');
    var $popupOutside = $('.popup-overlay[data-outside]');
    /* TO FIX MULTIPLE */
    var $popupScroll  = $('.popup-overlay[data-scroll]');

    var $main = $('#main');
    var mainHeight;

    getOffsets();
    updateScenes();

    $(window).resize(function() {
        getOffsets();
        updateScenes();
    });

    function getOffsets() {
        mainHeight     = $main.outerHeight();
    }

    function updateScenes() {

        if ( typeof controller !== 'undefined') {controller.destroy(true);}
        controller  = new ScrollMagic.Controller();

        if($popupScroll.length) {
            showPopupOnScroll();
        }
  
    } 
    
    /* CHECK IF THERE IS A TIMED POPUP ------------------------------
    ------------------------------ */
    if($popupTimer.length) {
        var time = $popupTimer.attr('data-timer')*1000;
        var name = $popupTimer.attr('data-name'); 

        if( getCookie('popup-'+name) != 'submited'){ 
            if(getCookie('popup-'+name) != 'closed' ){
                setTimeout(function(){
                    $popupTimer.addClass('active');
                    $body.addClass('popup-freeze');
                }, time);
            }
        }
    }

    $(document).mouseleave(function () {

        if($popupOutside.length) {
            var name = $popupTimer.attr('data-name'); 
            if( getCookie('popup-'+name) != 'submited'){ 
                if(getCookie('popup-'+name) != 'closed' ){
                    $popupOutside.addClass('active');
                    $body.addClass('popup-freeze');
                }
            }
        }
     });

    /* CLICK TO OPEN POPUP ------------------------------------------------------------------------ */
    $('a[href|="#popup"]').click(function() {
        var href = $(this).attr('href');
        var popupName = href.replace('#popup-', '');
        var $popup = $('.popup-overlay[data-name="'+popupName+'"');
        
        if($popup.length) {
            $body.addClass('popup-freeze');
            $('.popup-overlay[data-name="'+popupName+'"').addClass('active');
        }

        return false;
    });

    /* CLICK ON CLOSE BTN ------------------------------------------------------------------------ */
    $('.close-overlay').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var $overlay = $this.parents('.popup-overlay');

        if($popupTimer.length || $popupOutside.length) {
            var name = $popupTimer.attr('data-name'); 

            //COOKIE 
            setCookie( 'popup-'+name, 'closed', 30 );
        }

        $overlay.removeClass('active');
        $body.removeClass('popup-freeze');

    });

    /* ESCAPE ------------------------------------------------------------------------------------ */
    $(document).on('keyup',function(evt) {
        if (evt.keyCode == 27) {

            if($popupTimer.length || $popupOutside.length) {
                var name = $popupTimer.attr('data-name'); 

                //COOKIE 
                setCookie( 'popup-'+name, 'closed', 30 );
            }

            $('.popup-overlay').removeClass('active');
            $body.removeClass('popup-freeze');
        }
    });

    function showPopupOnScroll() {

        var scroll = $popupScroll.attr('data-scroll')/100; 
        var duration = mainHeight*scroll;
        
        new ScrollMagic.Scene({
            triggerElement: $('#main'),
            triggerHook: 0,
            offset: 0,
            duration: duration
        })
        //.addIndicators()
        .on("end", function (event) {
            var name = $popupScroll.attr('data-name'); 
            if( getCookie('popup-'+name) != 'submited'){ 
                if(getCookie('popup-'+name) != 'closed' ){
                    $popupScroll.addClass('active');
                    $body.addClass('popup-freeze');
                }
            }

            return false;
        })
        .addTo(controller);
    }

    /* GET COOKIE ------------------------------------------------------------------------------------ */
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
    }

    /* SET COOKIE ------------------------------------------------------------------------------------ */
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

});