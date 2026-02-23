$(document).ready(function(){

    var windowWidth;

    getOffsets();

    $(window).resize(function() {
        getOffsets();
    });

    function getOffsets() {
        windowWidth   = $(window).width();
    } 
    
    $('.btn-group-guides').find('.btn').on( "click", function() {
    	var $this = $(this);
    	var $btnHolder = $this.parents('.btn-group-guides');
    	var $container = $this.parents('.section-guides'); 

    	var $list = $container.find('.list-guides');

    	var type = $this.data("type");

    	$btnHolder.find('.btn').removeClass('active');
    	$this.addClass('active');

    	$list.find('li').removeClass('show');
    	$list.find('li[data-type="'+type+'"]').addClass('show');
    });

    $('.list-guides').find('li').on( "click", function() {
    	var $this = $(this);
    	var $container = $this.parents('.section-guides'); 

    	var $list = $this.parents('.list-guides');

    	var $guides = $container.find('.content-guides');

    	var guide = $this.data('guide');

    	$list.find('li').removeClass('active');
    	$this.addClass('active');

    	$guides.find('.content-guide').removeClass('show');
    	$guides.find('.content-guide[data-guide="'+guide+'"]').addClass('show');
    });

});