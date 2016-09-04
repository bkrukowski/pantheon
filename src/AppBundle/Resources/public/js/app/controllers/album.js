var
    $injections = ['$http', '$stateParams', '$scope', '$rootScope', '$element', '$state', 'flashMessages', 'dialogs', '$timeout'],
    controllerAlbum = function ($http, $stateParams, $scope, $rootScope, $element, $state, flashMessages, dialogs, $timeout) {
    $rootScope.title = 'Album ' + $stateParams.albumId + ' is loading...';
    var refresh = function () {
        $scope.isEmpty = $.isEmptyObject($scope.images);
        $timeout(function () {
            $rootScope.events.scroll();
        });
    };
    $scope.albumId = $stateParams.albumId;
    $rootScope.promises.album($stateParams.albumId).then(function (response) {
        $rootScope.title = 'Album ' + response.data.album.name;
        $scope.images = response.data.images;
        $scope.album = response.data.album;
        refresh();
        $rootScope.loader.hide();
    }, function () {
        flashMessages.pushError('Page does not exist!', 'Error 404');
        $state.transitionTo('home');
    });
    $scope.$on('$destroy', function () {
        $rootScope.loader.show();
    });
    $scope.pushPhoto = function (row) {
        $scope.images.push(row);
        refresh();
        $scope.$apply();
        $rootScope.promises.clearCacheForAlbum($scope.albumId);
        $rootScope.events.scroll();
    };
    $('#images-list').on('click', '.btn-remove', function (e) {
        e.preventDefault();
        var
            $this = $(this),
            photoId = $this.data('photoId'),
            albumId = $this.data('albumId');
        var onAccept = function () {
            var removePhoto = function () {
                $rootScope.promises.removePhoto(photoId);
                $rootScope.promises.clearCacheForAlbum(albumId);
                for (var i = 0; i < $scope.images.length; i++) {
                    if ($scope.images[i].id == photoId) {
                        $scope.images.splice(i, 1);
                        break;
                    }
                }
                refresh();
                // $scope.$apply();
            };
            removePhoto();
        };
        dialogs.confirm(onAccept);
    });
};
controllerAlbum.$inject = $injections;

galleryApp.controller('controller.album', controllerAlbum);