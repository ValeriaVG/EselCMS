'use strict';

var gulp = require('gulp');
var gutil = require('gulp-util');
var coffee = require('gulp-coffee');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');



var Server = require('karma').Server;


gulp.task('coffee-components', function() {
  return gulp.src('./public/src/components/*.coffee')
    .pipe(coffee({bare: true}).on('error', gutil.log))
    .pipe(gulp.dest('./public/admin/components'))
    .pipe(concat('components.js'))
    .pipe(gulp.dest('./public/admin'))
});

gulp.task('coffee-rest', function() {
  return gulp.src('./public/src/*.coffee')
    .pipe(coffee({bare: true}).on('error', gutil.log))
    .pipe(gulp.dest('./public/admin/'))
});

gulp.task('coffee',['coffee-components','coffee-rest'], function() {
  return gulp.src('./public/admin/*.js')
    .pipe(concat('admin.js'))
    .pipe(gulp.dest('./public/js/'))
});



gulp.task('minify-js',['coffee'],function(){
  gulp.src(['./public/admin/components.js','./public/admin/admin.js'])
    .pipe(concat('admin.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./public/js/'))
});



gulp.task('test',['coffee'], function (done) {
  new Server({
    configFile: __dirname + '/karma.conf.js',
    singleRun: true
  }, done).start();
});


gulp.task('default', ['coffee','minify-js']);
