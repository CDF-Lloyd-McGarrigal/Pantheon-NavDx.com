var gulp 		= require('gulp');
var gutil		= require('gulp-util');
var livereload 	= require('gulp-livereload');
var paths		= require('../../package.json').paths;
//dot env
require('dotenv').config({path: 'build.env'});

module.exports = function(){
	//individual watch tasks
	gulp.watch(paths.source + 'sass/**/**.scss', ['sass']);
	gulp.watch([paths.source + 'img/**', '!' + paths.source + 'img/**/*.processed.*'], ['image']);

	//trigger livereload on file changes
	livereload.listen();
	gulp.watch([process.env.SCRIPTS + '**', process.env.STYLES + '**', process.env.IMAGES + '**']).on('change', livereload.changed);

	//output that we're watching and livereload is running
	gutil.log(gutil.colors.green('Watching source files...'));
	gutil.log(gutil.colors.green('LiveReload initalized...'));
}