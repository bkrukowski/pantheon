const
    gulp = require('gulp'),
    del = require('del');

gulp.task('clean', function () {
    del.sync('output', {forced: true});
});