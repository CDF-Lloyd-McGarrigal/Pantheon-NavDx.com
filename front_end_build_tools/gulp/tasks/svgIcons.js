var gulp = require( 'gulp' );
var svg = require( 'gulp-svg-sprite' );
var paths = require( '../../package.json' ).paths;
// get env
require( 'dotenv' ).config({ path: 'build.env' });

var config = {
	shape:{
		dimension:{
			width:64,
			height:64
		}
	},
	mode: {
		symbol: {
			dest: process.env.ICONS,
			sprite: 'icons.svg',
			example: false,
			inline: true
		}
	},
	svg: {
		xmlDeclaration: false,
		doctypeDeclaration: false
	}
};

module.exports = function() {
	return gulp.src( paths.source + 'icons/**/*.svg' )
		.pipe( svg( config ) )
		.pipe( gulp.dest( '.' ) );
};
