var livereload = require('gulp-livereload');

module.exports = function(){
	gulp.watch('src/**/', ['styles']);
}