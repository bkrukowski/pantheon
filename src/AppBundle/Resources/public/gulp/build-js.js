const
    gulp = require('gulp'),
    include = require('gulp-include');

gulp.task('build-js', ['build-html'], function () {
    return gulp.src('js/main.js')
        .pipe(include())
        .pipe(gulp.dest('output/js'));
});