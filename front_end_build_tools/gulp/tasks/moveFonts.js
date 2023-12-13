var gulp 	= require('gulp');
var rename	= require('gulp-rename');
var paths	= require('../../package.json').paths;
//dot env
require('dotenv').config({path: 'build.env'});

module.exports = function(){
	return gulp.src([paths.source + 'fonts/**', '!./**/config.json'])
		.pipe(rename(function(path){
			//rename fonts if set
			if(process.env.RENAME_FONTS){
				if(path.basename == process.env.RENAME_TARGET){
					path.basename = process.env.RENAME_NAME;
				}
			}
		}))
		.pipe(gulp.dest(process.env.FONTS));
}