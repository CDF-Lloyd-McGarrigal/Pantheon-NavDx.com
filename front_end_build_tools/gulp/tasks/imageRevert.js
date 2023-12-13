var gulp 		= require('gulp');
var paths		= require('../../package.json').paths;

module.exports = function(){
	var imagesPath = paths.source + 'img/**';
	require('../utils/unrename')(imagesPath);
}