galleryApp.run(['$rootScope', '$http', '$cacheFactory', 'BASE_AJAX_PATH', function ($rootScope, $http, $cacheFactory, BASE_AJAX_PATH) {
    var post = function (data) {
        data.method = 'POST';
        data.headers = data.headers || {};
        data.headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
        data.data = $.param( data.data || {} );
        return $http( data );
    };

    var
        screenWidth = window.screen.width,
        screenHeight = window.screen.height,
        cache = {},
        cachedAlbumsIds = {};

    var cacheKeyForAlbum = function (albumId) {
        return 'httpAlbumGet_' + albumId;
    };

    $rootScope.promises = {
        album: function (albumId) {
            var cacheKey = cacheKeyForAlbum(albumId);
            if (!(cacheKey in cache)) {
                cache[cacheKey] = $cacheFactory(cacheKey);
            }
            return $http({
                method: 'GET',
                url: BASE_AJAX_PATH + '/album',
                params: {id: albumId, screenWidth: screenWidth, screenHeight: screenHeight},
                cache: cache[cacheKey]
            }).then(function (response) {
                cachedAlbumsIds[albumId] = true;
                return response;
            });
        },

        isAlbumPromiseCached: function (albumId) {
            return albumId in cachedAlbumsIds;
        },

        albumList: function () {
            return $http({
                method: 'GET',
                url: BASE_AJAX_PATH + '/album/getList',
                cache: false
            });
        },

        newAlbum: function (name) {
            return post({
                url: BASE_AJAX_PATH + '/album/newAlbum',
                data: {data: {name: name}}
            });
        },

        clearCacheForAlbum: function (albumId) {
            var key = cacheKeyForAlbum(albumId);
            if (key in cache) {
                cache[key].removeAll();
            }

            if (albumId in cachedAlbumsIds) {
                delete cachedAlbumsIds[albumId];
            }
        },

        removePhoto: function (photoId) {
            return post({
                url: BASE_AJAX_PATH + '/photo/removeOne',
                data: {
                    photoId: photoId
                }
            });
        },

        isLogged: function () {
            return $http({
                method: 'GET',
                url: BASE_AJAX_PATH + '/isLogged'
            });
        },

        logIn: function (data) {
            return post({
                url: BASE_AJAX_PATH + '/logIn',
                data: data
            });
        },

        logOut: function () {
            return $http({
                url: BASE_AJAX_PATH + '/account/logout'
            });
        },

        removeAlbum: function (albumId) {
            return post({
                url: BASE_AJAX_PATH + '/album/removeAlbum',
                data: {
                    albumId: albumId
                }
            });
        },

        changeAlbumName: function (albumId, newName) {
            return post({
                url: BASE_AJAX_PATH + '/album/changeName',
                data: {
                    albumId: albumId,
                    name: newName
                }
            });
        }
    };
}]);