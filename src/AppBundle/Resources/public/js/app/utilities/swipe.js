galleryApp.run(['$rootScope', function ($rootScope) {
    var swipes = {};

    $rootScope.swipe = {
        create: function (albumId, items, _index) {
            var pswpElement = document.querySelectorAll('.pswp')[0];
            var getArrayIndexByUrlIndex = function (index) {
                for (var i = 0; i < items.length; i++) {
                    if (items[i].id == index) {
                        return i;
                    }
                }
                return null;
            };
            var index = getArrayIndexByUrlIndex(_index);
            var options = {
                history: false,
                index: index,
                showHideOpacity: true,
                forceProgressiveLoading: true,
                getThumbBoundsFn: function (index) {
                    var el = document.querySelector('#photo-' + index);
                    if (!el) {
                        return;
                    }
                    var pageYScroll = window.pageYOffset || document.documentElement.scrollTop;
                    var rect = el.getBoundingClientRect();
                    return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
                },
                barsSize: {top: 0, bottom: 'auto'},
                preload: [1, 3]
            };
            var result = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
            swipes[ albumId ] = result;
            result.goToFromUrl = function (indexFromUrl) {
                this.goTo(getArrayIndexByUrlIndex(indexFromUrl));
            };
            result.getCurrentUrlIndex = function () {
                return items[this.getCurrentIndex()].id;
            };
            return result;
        },

        destroy: function (albumId) {
            delete swipes[ albumId ];
        },

        exists: function (albumId) {
            return typeof swipes[ albumId ] !== 'undefined';
        },

        get: function (albumId) {
            return swipes[ albumId ];
        }
    };
}]);