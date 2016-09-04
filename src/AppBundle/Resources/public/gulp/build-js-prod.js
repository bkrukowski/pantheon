const
    gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename');

gulp.task('build-js-prod', ['build-js'], function () {
    return gulp.src('output/js/main.js')
        .pipe(uglify({
            mangle: false,
            preserveComments: 'all'
        }))
        // .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('output/js'));
});