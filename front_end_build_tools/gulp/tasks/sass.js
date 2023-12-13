var gulp			= require( 'gulp' );
var sass			= require( 'gulp-sass' );
var sourcemaps		= require( 'gulp-sourcemaps' );
var cssnano			= require( 'gulp-cssnano' );
var handleError		= require( '../utils/handleError' );
var paths			= require( '../../package.json' ).paths;
// dot env
require( 'dotenv' ).config({ path: 'build.env' });

// this is handy - put the exact browser support here for prefixing
// last 2 versions - handles evergreen browsers
// IE8 for IE 8-10 support (-ms-)
// ios8 for old iOS
var supported = [
	'last 2 versions',
	'ie >= 8',
	'ios 8'
];

module.exports = function() {
	return gulp.src( paths.source + 'sass/**/*.scss' )
		.pipe( sourcemaps.init() )
		.pipe( sass() )
		.on( 'error', handleError )
		// .pipe(autoprefixer({browsers: ['last 10 versions', 'ie 9', 'ie 10', 'ie 11']}))
		.pipe( cssnano({
			autoprefixer: {
				browsers: supported,
				add: true
			}
		}) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( process.env.STYLES ) );
};
