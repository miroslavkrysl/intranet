var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');
var tildeImporter = require('node-sass-tilde-importer');

var scripts = [
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap-v4-dev/dist/js/bootstrap.min.js',
    'resources/assets/js/*.js'
];

var style = [
    'resources/assets/sass/app.scss'
];

function handleError(error) {
    console.log(error.toString());
    this.emit('end');
}

gulp.task('js', function(){
   gulp.src(scripts)
   .pipe(concat('app.js'))
   .pipe(gulp.dest('public/js/'));
});

gulp.task('scss', function(){
   gulp.src(style)
   .pipe(sass({
      importer: tildeImporter
   }))
   .on('error', handleError)
   .pipe(cleanCSS())
   .pipe(gulp.dest('public/css/'));
});

gulp.task('watch', ['js', 'scss'], function() {
    gulp.watch(['application/assets/js/*.js'], ['js']);
    gulp.watch(['application/assets/sass/*.scss'], ['css']);
});

gulp.task('bundle', ['js','scss'], function() {
});

gulp.task('default', ['js','scss'], function(){
});
