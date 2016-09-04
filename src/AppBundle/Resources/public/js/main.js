//= include ../bower_components/jquery/dist/jquery.min.js
jQuery.noConflict();
//= include ../bower_components/bootstrap/dist/js/bootstrap.min.js
//= include ../bower_components/angular/angular.min.js
//= include ../bower_components/angular-ui-router/release/angular-ui-router.min.js
//= include ../bower_components/photoswipe/dist/photoswipe.min.js
//= _include ../bower_components/photoswipe/dist/photoswipe-ui-default.min.js
//= include photoswipe-ui/photoswipe-ui.js
//= include ../bower_components/ng-flow/dist/ng-flow.min.js
//= include ../bower_components/ng-flow/dist/ng-flow-standalone.min.js
//= include ../bower_components/modernizr/modernizr.js
//= include ../bower_components/angular-bootstrap/ui-bootstrap.min.js
//= include ../bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js

(function ($) {
    "use strict";
    var galleryApp = angular.module('aGallery', ['ui.router', 'flow', 'ui.bootstrap']);
    //= include utilities/**/*.js
    //= include extra/**/*.js
    //= include ../output/js/templates.js
    //= include app/constants/**/*.js
    //= include app/utilities/**/*.js
    //= include app/events/**/*.js
    //= include app/config/**/*.js
    //= include app/controllers/**/*.js
})(jQuery);