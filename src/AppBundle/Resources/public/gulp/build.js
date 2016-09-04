const gulp = require('gulp');

gulp.task('build', ['clean'], function () {
    return gulp.start(['build-css', 'build-js']);
});