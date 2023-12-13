var gulp 	= require('gulp');
var gutil 	= require('gulp-util');

module.exports = function(err){
	//log that an error occured
	gutil.log(gutil.colors.red('Error!:'), err.message);
	
	//end the task so everything else continues
	this.emit('end');
}