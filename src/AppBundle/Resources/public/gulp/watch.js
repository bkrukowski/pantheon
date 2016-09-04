const gulp = require('gulp');

gulp.task('watch', ['build'], function () {
    gulp.watch('css/**/*.css', ['build-css']);
    gulp.watch(['js/**/*.js', 'output/js/templates.js'], ['build-js']);
    gulp.watch('html/**/*.html', ['build-html']);
});