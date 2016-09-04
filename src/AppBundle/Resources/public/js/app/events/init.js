var $inject = ['$rootScope', '$window'];
var fn = function ($rootScope, $window) {
    var $jWindow = $($window);
    $jWindow.on('scroll', $rootScope.events.scroll);
    $jWindow.on('resize', $rootScope.events.scroll);
};
fn.$inject = $inject;

galleryApp.run(fn);