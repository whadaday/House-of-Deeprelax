$(document).ready(function(){

    var windowWidth;

    getOffsets();

    $(window).resize(function() {
        getOffsets();
    });

    function getOffsets() {
        windowWidth   = $(window).width();
    } 
    
    $('.btn-group-pages').find('.btn').on( "click", function() {
    	var $this = $(this);
    	var $btnHolder = $this.parents('.btn-group-pages');
    	var $container = $this.parents('.section-session-types'); 

    	var $list = $container.find('.list-pages');

    	var type = $this.data("type");

    	$btnHolder.find('.btn').removeClass('active');
    	$this.addClass('active');

    	$list.find('li').removeClass('show');
    	$list.find('li[data-type="'+type+'"]').addClass('show');
    });

    $('.list-pages').find('li').on( "click", function() {
    	var $this = $(this);
    	var $container = $this.parents('.section-session-types'); 

    	var $list = $this.parents('.list-pages');

    	var $pages = $container.find('.content-pages');

    	var page = $this.data('page');

    	$list.find('li').removeClass('active');
    	$this.addClass('active');

    	$pages.find('.content-page').removeClass('show');
    	$pages.find('.content-page[data-page="'+page+'"]').addClass('show');
    });

});