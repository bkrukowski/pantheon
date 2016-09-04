const gulp = require('gulp');

gulp.task('build-prod', ['bower'], function () {
    return gulp.start(['build-css', 'build-js-prod']);
});