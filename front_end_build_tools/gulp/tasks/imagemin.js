var gulp 		= require('gulp');
var imagemin 	= require('gulp-imagemin');
var rename		= require('gulp-rename');
var paths		= require('../../package.json').paths;
//dot env
require('dotenv').config({path: 'build.env'});

module.exports = function(){
	return gulp.src([paths.source + 'img/**', '!' + paths.source + 'img/**/*.processed.*'])
		.pipe(gulp.dest(paths.source + 'img'))
		.pipe(imagemin({
			optimizationLevel:4,
			progressive:true
		}))
		.pipe(gulp.dest(process.env.IMAGES));
}