var $inject = ['$rootScope', '$scope', '$interval', '$element', '$window', 'SITE_NAME', 'flashMessages'];
var controllerMenu = function ($rootScope, $scope, $interval, $element, $window, SITE_NAME, flashMessages) {
    $scope.siteName = SITE_NAME;
    $rootScope.isLogged = false;
    var refreshLoginState = function () {
        $rootScope.promises.isLogged().then(function (response) {
            $rootScope.isLogged = response.data.isLogged;
        });
    };
    refreshLoginState();
    var $loginStatePromise = $interval(refreshLoginState, 60000);
    $scope.$on('$destroy', function() {
        $interval.cancel($loginStatePromise);
    });
    $('form', $element).submit(function (e) {
        e.preventDefault();
        $rootScope.loader.show();
        var data = $window.utilities.serializeForm($(this));
        $rootScope.promises.logIn(data).then(function (result) {
            if (result.data.success) {
                $rootScope.isLogged = true;
            } else {
                var $btnMenu = jQuery('#btn-collapse-menu');
                if ($btnMenu.is(':visible') && !$btnMenu.hasClass('collapsed')) {
                    $btnMenu.click();
                }
                flashMessages.clearDisplayed();
                flashMessages.pushError(result.data.message, 'Cannot login');
            }
            $('.dropdown.open .dropdown-toggle').dropdown('toggle');
            $rootScope.loader.hide();
        });
    });
    $('[href="#logout"]', $element).click(function (e) {
        e.preventDefault();
        $rootScope.loader.show();
        $rootScope.promises.logOut().then(function (result) {
            if (result.data.success) {
                $rootScope.isLogged = false;
            }
            $rootScope.loader.hide();
        });
    });
};
controllerMenu.$inject = $inject;

galleryApp.controller('controller.menu', controllerMenu);