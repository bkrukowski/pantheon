var
    $inject = ['$scope', '$state', '$stateParams', '$rootScope', 'flashMessages'],
    controllerPhoto = function ($scope, $state, $stateParams, $rootScope, flashMessages) {
    var moveTo = function(index) {
        var
            $el = $('#photo-' + index),
            $window = $(window),
            windowOffsetTop = $window.scrollTop(),
            windowOffsetBottom = windowOffsetTop + $window.height(),
            elOffset = $el.offset(),
            margin = 30;
        if (typeof elOffset === 'undefined') {
            return;
        }
        var
            offsetTop = elOffset.top + margin,
            offsetBottom = offsetTop + $el.height() - margin;
        if (offsetTop >= windowOffsetTop && offsetTop <= windowOffsetBottom) {
            return;
        }
        if (offsetBottom >= windowOffsetTop && offsetBottom <= windowOffsetBottom) {
            return;
        }
        var top = $el.offset().top;
        top -= Math.round( $(window).height() / 2 ) - Math.round( $el.height() / 2 );
        if (top < 0) {
            top = 0;
        }
        $(window).scrollTop(top);
    };
    if (!$rootScope.swipe.exists($stateParams.albumId)) { // open photo
        var loaderVisible = false;
        if (!$rootScope.promises.isAlbumPromiseCached($stateParams.albumId)) {
            $rootScope.loader.show();
            loaderVisible = true;
        }
        $rootScope.promises.album($stateParams.albumId).then(function (response) {
            loaderVisible && $rootScope.loader.hide();
            var exists = false;
            for (var i = 0; i < response.data.images.length; i++) {
                if (response.data.images[i].id == $stateParams.photoId) {
                    exists = true;
                    break;
                }
            }
            if (!exists) {
                flashMessages.pushError('Photo does not exist!', 'Error 404');
                $rootScope.swipe.destroy($stateParams.albumId);
                $state.transitionTo('album', {albumId: $stateParams.albumId});
                return;
            }
            var gallery = $rootScope.swipe.create($stateParams.albumId, response.data.images, $stateParams.photoId);
            moveTo(gallery.getCurrentIndex());
            gallery.init();
            var onClose = function () {
                moveTo( gallery.getCurrentIndex() );
                $state.transitionTo('album', {albumId: $stateParams.albumId});
                $rootScope.swipe.destroy($stateParams.albumId);
            };
            var beforeChange = function () {
                moveTo(gallery.getCurrentIndex());
                !$rootScope.lockTransitionBeforeChange && $state.transitionTo('album.photo', {albumId: $stateParams.albumId, photoId: gallery.getCurrentUrlIndex()});
            };
            gallery.listen('close', onClose);
            gallery.listen('beforeChange', beforeChange);
        });
    } else { // back/next button
        var
            gallery = $rootScope.swipe.get($stateParams.albumId),
            indexFromUrl = $stateParams.photoId;
        if (gallery.getCurrentUrlIndex() != indexFromUrl) {
            moveTo(gallery.getCurrentIndex());
            $rootScope.lockTransitionBeforeChange = true;
            gallery.goToFromUrl(indexFromUrl);
            $rootScope.lockTransitionBeforeChange = false;
        }
    }
};
controllerPhoto.$inject = $inject;

galleryApp.controller('controller.album.photo', controllerPhoto);