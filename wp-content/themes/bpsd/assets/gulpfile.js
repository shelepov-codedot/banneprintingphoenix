'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');

gulp.task('sass', function (done) {
    gulp.src('./css/main.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            overrideBrowserslist:  ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('./css'));
    done();
});

gulp.task("watch", function() {
    gulp.watch('./css/main.scss', gulp.parallel('sass'));
    gulp.watch('./scss/**/*.scss', gulp.parallel('sass'));
});


var svgSprite = require('gulp-svg-sprite');

gulp.task('svgSprite', function () {
    return gulp.src('./img/icons/*.svg') // svg files for sprite
        .pipe(svgSprite({
                mode: {
                    stack: {
                        sprite: "./sprite.svg"  //sprite file name
                    }
                },
            }
        ))
        .pipe(gulp.dest('./img/'));
});