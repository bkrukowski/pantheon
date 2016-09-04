var $inject = ['$scope', '$rootScope', '$element', '$timeout', '$interval'];
var changeName = function ($scope, $rootScope, $element, $timeout, $interval) {
    $('form', $element).submit(function (e) {
        e.preventDefault();
        var newName = $(this).find('[name=name]').val();
        $rootScope.loader.show();
        $rootScope.promises.changeAlbumName($scope.album.id, newName).then(function (response) {
            if (response.data.success) {
                $scope.$parent.album.name = newName;
                $scope.errors = false;
                $rootScope.promises.clearCacheForAlbum($scope.albumId);
            } else {
                $scope.errors = response.data.errors;
            }
            $rootScope.loader.hide();
        });
    });
    $scope.$parent.$watch('album.name', function () {
        $('[name=name]', $element).val('');
    });
};
changeName.$inject = $inject;

galleryApp.controller('controller.albumChangeName', changeName);