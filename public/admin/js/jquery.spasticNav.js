(function($) { 
    $.fn.spasticNav = function(options) { 

        options = $.extend({
            overlap : 20,
            speed : 500,
            reset : 1500,
            color : '#0b2b61',
            easing : 'easeOutExpo',
            clear : null,
            backToSelected_callback: null
        }, options);

        return this.each(function() {

            var nav = $(this),
                currentPageItem = $('.selected', nav),
                blob,
                reset;

            $('<li id="blob"></li>').css({
                width : $('.selected', nav).outerWidth(),
                height : $('.selected', nav).outerHeight() + options.overlap + 7,
                left : $('.selected', nav).position().left,
                top : $('.selected', nav).position().top - options.overlap / 2 + 7,
                backgroundColor : options.color
            }).appendTo(this);

            blob = $('#blob', nav);

            $('li:not(#blob)', nav).hover(function() {
                // mouse over
                clearTimeout(reset);
                blob.animate(
                    {
                        left : $(this).position().left,
                        width : $(this).outerWidth()
                    },
                    {
                        duration : options.speed,
                        easing : options.easing,
                        queue : false
                    }
                );
            }, function() {
                // mouse out
                reset = setTimeout(function() {
                    blob.animate({
                        width : $('.selected', nav).outerWidth(),
                        left : $('.selected', nav).position().left
                    }, options.speed, options.backToSelected_callback)
                }, options.reset);
            });
        }); // end each
    };
})(jQuery);