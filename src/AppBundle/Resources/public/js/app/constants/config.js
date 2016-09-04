(function (w, a) {
    var keys = ['SITE_NAME', 'URL_PATH_PREFIX', 'BASE_AJAX_PATH'];
    for (var i = 0; i < keys.length; i++) {
        var key = keys[i];
        if (typeof w[key] === "undefined") {
            throw 'Undefined variable ' + key;
        }
        a.constant(key, w[key]);
    }
})(window, galleryApp);