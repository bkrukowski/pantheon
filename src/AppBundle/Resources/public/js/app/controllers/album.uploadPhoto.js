var uploadPhoto = function ($scope) {
    $scope.$on('flow::fileSuccess', function (event, $flow, flowFile, response, flowChunk) {
        var response = JSON.parse( flowChunk.xhr.responseText );
        if (response.success) {
            $scope.pushPhoto( response.result )
        } else {
            // TODO
            alert('Unexpected error [' + response.errorName + ']!');
        }
    });
};

galleryApp.controller('controller.album.uploadPhoto', uploadPhoto);