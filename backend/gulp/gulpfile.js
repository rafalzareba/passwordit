"use strict";

const gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify')
;


gulp.task('scripts', function () {
    // lista plikow js
    var scrptSrc = [
            '../web/lib/popper.js/popper.min.js',
            '../web/lib/bootstrap/dist/js/bootstrap.js',
            '../web/lib/ajaxcrud/ajaxcrud.js',
        ],
        scrptDst = '../web/js';

    return gulp.src(scrptSrc)
        .pipe(concat('vendor.js'))
        .pipe(uglify())
        .pipe(gulp.dest(scrptDst))
        ;
});

gulp.task(
    'default',
    gulp.series('scripts')
);

