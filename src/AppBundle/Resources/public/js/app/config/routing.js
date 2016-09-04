var
    $inject = ['$stateProvider', '$urlRouterProvider', '$locationProvider', 'URL_PATH_PREFIX'],
    config = function ($stateProvider, $urlRouterProvider, $locationProvider, URL_PATH_PREFIX) {    $locationProvider.html5Mode({
        enabled: true,
        requireBase: false,
        rewriteLinks: true
    });
        var realPrefix = URL_PATH_PREFIX === '/' ? '' : URL_PATH_PREFIX;
        var homeUrl = URL_PATH_PREFIX === '' ? '/' : URL_PATH_PREFIX;
        $urlRouterProvider.otherwise(homeUrl);
        $stateProvider
            .state('home', {
                url: homeUrl,
                templateUrl: 'home.html',
                controller: 'controller.home'
            })
            .state('album', {
                url: realPrefix + '/album-{albumId:int}',
                templateUrl: 'album.html',
                controller: 'controller.album'
            })
            .state('album.photo', {
                url: '/photo-{photoId:int}',
                views: {
                    photo: {
                        controller: 'controller.album.photo'
                    }
                }
            })
            .state('newAlbum', {
                url: realPrefix + '/new-album',
                templateUrl: 'newAlbum.html',
                controller: 'controller.newAlbum'
            });
    };

config.$inject = $inject;

galleryApp.config(config);