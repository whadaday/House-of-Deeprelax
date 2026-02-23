jQuery(function(){

    $('.tooltip')
    .mouseenter(function() {
        var title = $(this).attr('title');
        // $(this).attr('title', '');
        $(this).data('title' ,20);
    })
    .mouseleave(function() {
        var title = $(this).attr('data-title');
        $(this).attr('data-title', '');
        $(this).attr('title', title);
    });
});
 