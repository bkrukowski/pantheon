galleryApp.run(['$rootScope', '$http', function ($rootScope, $http) {
    var $loader = jQuery('#loader');

    $rootScope.loader = {
        show: function () {
            $loader.show();
        },

        hide: function () {
            $loader.hide();
        }
    };
}]);