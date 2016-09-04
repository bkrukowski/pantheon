jQuery(function() {
    var $html = jQuery('html');
    if (Modernizr.touch) {
        $html.addClass('touch');
    }
});