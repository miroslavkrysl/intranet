var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');
var tildeImporter = require('node-sass-tilde-importer');

var scripts = [
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/popper.js/dist/umd/popper.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js'
];
var scss = [
    'resources/assets/sass/app.scss',
    'node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.css'
];

function handleError(error) {
    console.log(error.toString());
    this.emit('end');
}

gulp.task('js', function(){
   gulp.src('resources/assets/js/*.js')
       .pipe(gulp.dest('public/js/'));
   gulp.src(scripts)
       .pipe(gulp.dest('public/js/'));
});

gulp.task('scss', function(){
    gulp.src(scss)
        .pipe(sass({
            importer: tildeImporter
        }))
        .on('error', handleError)
        .pipe(concat('app.css'))
        .pipe(cleanCSS())
        .pipe(gulp.dest('public/css/'));
});

gulp.task('watch', ['js', 'scss'], function() {
    gulp.watch(['resources/assets/js/*.js'], ['js']);
    gulp.watch(['resources/assets/sass/*.scss'], ['scss']);
});

gulp.task('fa', function() {
    gulp.src('node_modules/font-awesome/fonts/**.*')
        .pipe(gulp.dest('public/fonts/'));
});

gulp.task('bundle', ['js','scss'], function() {
});

gulp.task('default', ['js','scss', 'fa'], function(){
});
