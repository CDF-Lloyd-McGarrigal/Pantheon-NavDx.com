var gulp 		= require( 'gulp' );
var browserify 	= require( 'browserify' );
var watchify	= require( 'watchify' );
var source 		= require( 'vinyl-source-stream' );
var buffer		= require( 'vinyl-buffer' );
var uglify		= require( 'gulp-uglify' );
var sourcemaps 	= require( 'gulp-sourcemaps' );
var gutil		= require( 'gulp-util' );
var handleError = require( '../utils/handleError' );
var babelify 	= require( 'babelify' );
var packageJson	= require( '../../package.json'  );
var paths		= packageJson.paths;
var filenames	= packageJson.filenames;
var deps		= packageJson.dependencies;
//dot env
require( 'dotenv' ).config({path: 'build.env'});
// get list of dependencies from the package.json file
var dependencies = Object.keys( deps );

module.exports = function(){
	var src = paths.source + 'js/main.js'
	if(global.isWatching){
		var bundler = watchify(
            browserify(src)
            .transform( babelify )
            .transform({
                global: true
            }, 'browserify-shim' )
        );
	}else{
		var bundler = browserify(src)
            .transform( babelify )
            .transform({
                global: true
            }, 'browserify-shim' );
	}

	var bundle = function(){
		return bundler
			.external( dependencies )
			.bundle()
			.on('error', handleError)
			.pipe(source('./' + filenames.appJs + '.js'))
			.pipe(buffer())
			.pipe(sourcemaps.init())
			.pipe(uglify())
			.pipe(sourcemaps.write('.'))
			.pipe(gulp.dest(process.env.SCRIPTS));
	};

	if(global.isWatching) {
		bundler.on('update', function(){
			bundle();
			gutil.log(gutil.colors.green('Change Detected:'), 'Scripts Rebundling');
		});
	}

	return bundle();
};
