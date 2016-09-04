var $inject = ['$rootScope', '$window', '$location', 'flashMessages'];
var fn = function ($rootScope, $window, $location, flashMessages) {
    $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
        if (fromState.name === 'album.photo' && toState.name === 'album') {
            $rootScope.swipe.exists(fromParams.albumId) && $rootScope.swipe.get(fromParams.albumId).close();
        }
        if (!(fromState.name === 'album.photo' && (toState.name === 'album.photo' || toState.name === 'album'))
            && toState.name !== 'album.photo'
        ) {
            $rootScope.loader.show();
        }
    });

    var refreshGoogleAnalytics = function () {
        if (!$window.ga) {
            return;
        }
        $window.ga('send', 'pageview', { page: $location.path() });
    };

    $rootScope.$on('$stateChangeSuccess', function () {
        refreshGoogleAnalytics();
        flashMessages.clearDisplayed();
    });
};
fn.$inject = $inject;

galleryApp.run(fn);