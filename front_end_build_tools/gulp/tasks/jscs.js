var gulp = require( 'gulp' );
var jscs = require( 'gulp-jscs' );
// paths
var paths = require( '../../package.json' ).paths;

module.exports = function() {
	return gulp.src([paths.source + '/js/**.js', '!' + paths.source + '/js/vendor/**'])
		.pipe( jscs() )
		.pipe( jscs.reporter() );
};
