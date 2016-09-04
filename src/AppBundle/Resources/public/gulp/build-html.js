const
    gulp = require('gulp'),
    ngTemplates = require('gulp-ng-templates'),
    htmlMin = require('gulp-htmlmin');

gulp.task('build-html', function () {
    return gulp.src('html/**/*.html')
        .pipe(htmlMin({
            collapseWhitespace: true,
            caseSensitive: true
        }))
        .pipe(ngTemplates({
            module: 'aGallery',
            filename: 'templates.js',
            standalone: false
        }))
        .pipe(gulp.dest('output/js'));
});