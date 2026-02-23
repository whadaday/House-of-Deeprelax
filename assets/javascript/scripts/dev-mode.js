jQuery(function ($) {

    window.addEventListener("keydown", (event) => {
      if(event.key == ',') {
        $('.grid-view').toggleClass('active');
      }
    }, true);

});