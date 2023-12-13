# Tasks

All tasks reference file paths defined in `package.json` for input and `build.env` for output

## browserify

JS modules compilation using `browserify` with minification and source mapping

The `browserify` task should be fairly simple, but a bit of extra stuff is going on to tie it into the `watchify` task.  A global variable `isWatching` has been defined to determine whether or not to run the regular browserify task, or the watchify task.  Watchify is used to incrementally recompile JavaScript for much faster compilation on `watch`.

**Docs**

* [https://www.npmjs.com/package/browserify](https://www.npmjs.com/package/browserify)
* [https://www.npmjs.com/package/watchify](https://www.npmjs.com/package/watchify)
* [https://www.npmjs.com/package/gulp-sourcemaps](https://www.npmjs.com/package/gulp-sourcemaps)

## sass

SASS compilation and minification task with source mapping.
Autoprefixing is handled automatically as a part of the minification process, a variable (`supported`) is available to handily specify all supported browsers to the autoprefixer.

**Docs**

* [https://www.npmjs.com/package/gulp-sass](https://www.npmjs.com/package/gulp-sass)
* [https://www.npmjs.com/package/gulp-cssnano](https://www.npmjs.com/package/gulp-cssnano)
* [https://www.npmjs.com/package/gulp-sourcemaps](https://www.npmjs.com/package/gulp-sourcemaps)

## imagemin

_Lossless_ image compression and optimization for `.jpg` and `.png` files.  The level of compression can be adjusted via the `optimizationLevel` and `progressive` options for `.png` and `.jpg` respectively.  The task only looks for images without a `.processed` in the filename in the `img` directory in the source path.

**Docs**

* [https://www.npmjs.com/package/gulp-imagemin](https://www.npmjs.com/package/gulp-imagemin)

## Move Fonts

The `moveFonts` task takes fonts from the source directory and moves them to the output directory.  If set, a set of fonts can be renamed - this is useful for generated font files like icon fonts that change throughout the course of a project.

## doWatch

This is the actual watch task with calls to `gulp.watch` for the `sass` and `image` tasks.  This task also sets up LiveReload and some pretty output to display when tasks are firing.

## setWatch

This task sets the `isWatching` global which triggers `browserify` to use `watchify` for watching and faster compilation.

# Utilities

## handleError

`handleError` is used in the `browserify` tasks to intercept a compile error, output it in a readable format, end end the stream (important).  If the stream is not ended, the watch task hangs and no longer triggers.

## rename

`rename` is used in the `image` task defined in `gulpfile.js` to rename files after they are processed.  This allows the `imagemin` task to only run on non-processed files, while still preserving the original unoptimized image file.