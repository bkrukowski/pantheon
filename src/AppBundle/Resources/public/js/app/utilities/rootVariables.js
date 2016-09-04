var
    $inject = ['$rootScope', 'BASE_AJAX_PATH'],
    rootVariables = function ($rootScope, BASE_AJAX_PATH) {
        $rootScope.BASE_AJAX_PATH = BASE_AJAX_PATH;
    };
rootVariables.$inject = $inject;
galleryApp.run(rootVariables);