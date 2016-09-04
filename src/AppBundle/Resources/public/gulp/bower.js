const
    gulp = require('gulp'),
    bower = require('gulp-bower');

gulp.task('bower', ['clean-bower'], function () {
    return bower();
});