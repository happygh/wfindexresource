(function ($) {
   
        $('div, img').slideShow({
            timeOut: 10000,
            showNavigation: true,
            pauseOnHover: true,
            swipeNavigation: true
        });

        var navbar=$('.navbar')

        navbar.animate({top: '-100px'}, function () {
            navbar.hide();
        });


}(jQuery));