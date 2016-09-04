galleryApp.run(['$rootScope', '$http', function ($rootScope, $http) {
    $rootScope.events = $rootScope.events || {};
    $rootScope.events.scroll = function () {
        var $window = $(window),
            windowOffsetTop = $window.scrollTop(),
            windowOffsetBottom = windowOffsetTop + $window.height(),
            numberOfPreloaded = 5;
        var functionFilterClass = function () {
            var $this = $(this);
            if (!$this.find('img:not(.img-loaded):not(.img-loading)').length) {
                return false;
            }
            return true;
        };
        var functionFilterOnVisibleArea = function () {
            var
                $this = $(this),
                offsetTop = $this.offset().top,
                offsetBottom = offsetTop + $this.height();
            if (offsetTop >= windowOffsetTop && offsetTop <= windowOffsetBottom) {
                return true;
            }
            if (offsetBottom >= windowOffsetTop && offsetBottom <= windowOffsetBottom) {
                return true;
            }
            return false;
        };
        var $images = $('.lazy-images')
            .find('img')
            .closest('a')
            .filter(functionFilterClass)
            .filter(functionFilterOnVisibleArea);
        var
            $extra = $(),
            $next = $images.filter(':last').closest('li'),
            $prev = $images.filter(':first').closest('li');
        for (var i=0; i<numberOfPreloaded; i++) {
            $next = $next.next();
            $prev = $prev.prev();
            $extra = $extra.add( $next.children('a') ).add( $prev.children('a') );
        }
        $images = $images.add( $extra.filter(functionFilterClass) );

        $images.each(function () {
            var $img = $(this).find('img');
            var src = $img.attr('realsrc');
            $img.one('load', function () {
                var $this = $(this);
                $this.addClass('img-loaded').removeClass('img-loading');
                $this.closest('.preloader').removeClass('preloader');
            });
            $img.attr('src', src);
            $img.addClass('img-loading');
        });
    };
}]);