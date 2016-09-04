var $inject = ['$scope', '$http', '$rootScope', '$timeout', '$element', '$uibModal', '$log', 'dialogs'];
var controllerHome = function ($scope, $http, $rootScope, $timeout, $element, $modal, $log, dialogs) {
    var reloadAlbums = function () {
        $rootScope.promises.albumList().then(function (response) {
            $scope.albums = response.data.albums;
            $rootScope.loader.hide();
        });
        $timeout(function () {
            $rootScope.events.scroll();
        });
    };
    reloadAlbums();
    $scope.$watch("albums", function (value) {
        $timeout(function () {
            $rootScope.events.scroll();
            $('.list__item > .btn-remove', $element).click(function (e) {
                e.preventDefault();
                removeAlbum($(this).data('album_id'));
            });
        });
    });
    $rootScope.title = 'Home';

    var removeAlbum = function (albumId) {
        dialogs.confirm(function () {
            $rootScope.loader.show();
            $rootScope.promises.removeAlbum(albumId).then(function () {
                reloadAlbums();
            });
        }, function (source) {
            $log.info('Modal dismissed at: ' + new Date() + ' [' + source + ']');
        });
    };
}
controllerHome.$inject = $inject;

galleryApp.controller('controller.home', controllerHome);