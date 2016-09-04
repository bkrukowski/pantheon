var $inject = ['$rootScope', '$state', '$scope'];
var controllerNewAlbum = function ($rootScope, $state, $scope) {
    $rootScope.loader.hide();
    if (!$rootScope.isLogged) {
        $state.transitionTo('home');
    }
    var $form = $('#newAlbumForm');
    $form.submit(function (e) {
        e.preventDefault();
        $rootScope.loader.show();
        var name = $form.find('[name="name"]').val();
        $rootScope.promises.newAlbum(name).then(function (response) {
            if (response.data.success) {
                $state.transitionTo('album', {albumId: response.data.object.id});
            } else {
                $scope.errors = response.data.errors;
                $rootScope.loader.hide();
            }
        });
    });
    $rootScope.title = 'Add new album';
    var $input = $('#formAlbumName');
    if ($input.length) {
        $('#formAlbumName')[0].focus();
    }
};
controllerNewAlbum.$inject = $inject;

galleryApp.controller('controller.newAlbum', controllerNewAlbum);