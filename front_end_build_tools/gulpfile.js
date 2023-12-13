// define gulp and paths
var gulp	= require( 'gulp' );
var paths	= require( './package.json' ).paths;

/**
 * function to pull in individual tasks from tasks folder
 * @param  {string} task the task name which should correspond to a js file in the tasks folder
 */
function getTask( task ) {
	return require( paths.tasks + task );
}

// define the individual tasks
gulp.task( 'sass', getTask( 'sass' ) );
gulp.task( 'browserify', getTask( 'browserify' ) );
gulp.task( 'browserify:vendor', getTask( 'browserifyVendor' ) );
gulp.task( 'imagemin', getTask( 'imagemin' ) );
gulp.task( 'setWatch', getTask( 'setWatch' ) );
gulp.task( 'doWatch', getTask( 'doWatch' ) );
gulp.task( 'fonts', getTask( 'moveFonts' ) );
gulp.task( 'jscs', getTask( 'jscs' ) );
gulp.task( 'svg-icons', getTask( 'svgIcons' ) );


// compound task for image - runs imagemin then renames files
gulp.task( 'image', ['imagemin'], function() {
	var imagesPath = paths.source + 'img/**';
	require( './gulp/utils/rename' )( imagesPath );
});
// revert task for images so they can all can be reprocessed
gulp.task( 'image.revert', getTask( 'imageRevert' ) );

// define other compound tasks
gulp.task( 'scripts', ['browserify', 'browserify:vendor']);
gulp.task( 'styles', ['sass']);
// runs all tasks, sets watch global for browserify, then runs the actual watch task
gulp.task( 'watch', ['setWatch', 'sass', 'browserify'], getTask( 'doWatch' ) );
// default task
gulp.task( 'default', ['scripts', 'styles']);
