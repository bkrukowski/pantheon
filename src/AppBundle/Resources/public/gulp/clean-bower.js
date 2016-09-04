const
    gulp = require('gulp'),
    del = require('del');

gulp.task('clean-bower', function () {
    del.sync('bower_components', {forced: true});
});