const
    gulp = require('gulp'),
    cleanCss = require('gulp-clean-css'),
    sourceMaps = require('gulp-sourcemaps'),
    rename = require('gulp-rename');

gulp.task('build-css', function () {
    return gulp.src('css/main.css')
        .pipe(gulp.dest('output/css'))
        .pipe(sourceMaps.init())
        .pipe(cleanCss({keepSpecialComments: '*', relativeTo: 'css'}))
        // .pipe(rename({suffix: '.min'}))
        .pipe(sourceMaps.write('.'))
        .pipe(gulp.dest('output/css'));
});