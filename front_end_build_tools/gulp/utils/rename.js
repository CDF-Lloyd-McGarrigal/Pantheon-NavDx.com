var glob 	= require('glob');
var path	= require('path');
var fs		= require('fs');

module.exports = function(files){
	glob(files, function(err, files){
		//loop thorugh each path
		files.forEach(function(file){
			//parse path for parts
			var parsed = path.parse(file);
			//fs.lstatSync will easily let us figure out if we have a file or path
			var stats = fs.lstatSync(parsed.dir + '/' + parsed.base);
			//only rename files
			if(stats.isFile()){
				//if there is a non-processed version of the processed file, removed the processed file
				//we can assume the non-processed version is newer
				if(parsed.name.indexOf('processed') != -1){
					if(file.indexOf(parsed.dir + '/' + parsed.name.replace('.processed', '') + parsed.ext) != -1){
						fs.unlink(file);
					}
				}else{
					fs.renameSync(file, parsed.dir + '/' + parsed.name + '.processed' + parsed.ext);
				}
			}
		});
	})
}