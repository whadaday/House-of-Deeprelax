$(document).ready(function(){

    var windowWidth;

    getOffsets();

    $(window).resize(function() {
        getOffsets();
    });

    function getOffsets() {
        windowWidth   = $(window).width();
    } 

    $('.list-moments a').click(function(e) {
        var $this = $(this);
        var $section = $this.parents('.section');
        var $list = $this.parents('.list-moments');
        var $item = $this.parent('li');
        var track = $item.data('track');
        var index = $item.index();
        var $text = $list.next('.list-moments-description');
        var $player = $section.find('.audio-player');
        var $container = $section.find('.player-holder');
        var $btnPlay = $section.find('.btn-play');

        // class for loading new track
        $container.addClass('reload');

        $player.attr('src', track);
        $list.find('li').removeClass('active');
        $item.addClass('active');
        $text.find('li').removeClass('active');
        $text.find('li').eq(index).addClass('active');

        $player.get(0).load();
        $player.get(0).addEventListener("loadeddata", loadTrack);

        var loadTrack = setTimeout(function() {
            $player.get(0).play();
            $btnPlay.addClass('playing');
            $container.removeClass('reload');
        }, 0);

        
    });

});