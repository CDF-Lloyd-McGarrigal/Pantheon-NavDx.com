# Front End Build Tools

## Quickstart (Copy me to the root README.md)

 - **Requires Node v8.xx or newer!**
 - Copy `build.env.sample` to `build.env`
 - Check the directiories within `build.env`, if they arent already pointing to the site root, update these values
 - The buld tools require `node`, and `npm`, preferably `yarn` over `npm`, but either will do
 - `yarn install` or `npm install` from the `/frontend-build-tools` directory to install dependencies
 - `gulp` will run all default tasks
 - `gulp styles` will run the styles compilation
 - `gulp scripts` will run the scripts compilation
 - `gulp watch` watches for script or style changes and triggers the appropriate task

NOTE: Compiled scripts and styles are ignored from the repo and will not be updated after pulling.  It's generally a good idea to `gulp` after pulling in updates.

DOUBLE-NOTE: Along the course of development, new dependencies may get added - if you encounter errors about "module not found" during compilation or run-time, just re-run `yarn install` or `npm install`.  Additionally, blowing away `node_modules` and re-running `yarn install` or `npm install` is a good way to fix strange issues that can crop up while running the build tasks.

See the README in `/front-end-build-tools` for a more long-winded explanation of this.


## Output

Wherever you install this folder and run `gulp`, folders and files in **build.env** get created. By default, these are currently prefixed by `../public_html/content/themes/theme_name/`.

In tree form:

```
.
├── front-end-build-tools
└── public_html
```


## Installation and Configuration (long version, dont copy me)

Yarn is now the method of choice to install node modules for the front end build tools (formerly npm).  The reason for this being the `yarn.lock` file.  The lockfile specifies version numbers for each module and dependency installed.  This lock file allows for the build tools to be installed with consistient versions across systems.

Additionally, yarn does some neat extra stuff that npm does not do (it's faster, local caching, better dependency handling, etc): [Learn More](https://yarnpkg.com/)

Yarn can be installed using `brew`, or `npm` - [brew](https://yarnpkg.com/en/docs/install#mac-tab) [npm](https://yarnpkg.com/en/docs/install#alternatives-tab)

Usage of Gulp and the included build tools depends on installation and use of node and yarn.  With node and yarn installed, run `yarn install` to download the required packages for the included build tools.

The following paths can be configured through the included `package.json` file:

*  `source` - The location of the source files that serve as inputs

The following paths can be configured through the `build.env` file.

*  `scripts` - The output location for the final JavaScript files
*  `styles` - The output location for the final CSS files
*  `images` - The output location for the optimized images

The `build.env` file is not tracked in the repo, however a sample file is provided.

## Tasks
In addition to the base tasks defined, the following compound tasks are available to run from the command line and defined in `gulpfile.js`:

* `styles` - runs the `sass` and `image` tasks for SASS compilation and image optimization.
* `scripts` - runs the `browserify` task.
* `watch` - watches the `css`, `js`, and `img` directories for any file changes, fires appropriate tasks in response, and launches LiveReload
* `default` - runs the `style` and `scripts` task.

Please see the README file in the task folder for further documentation.

## Compiling Styles

SASS files in the `sass` directory within the source files path will be compilied, minified, and written to the specified output directory along with a sourcemap file.

## Compiling Scripts

JS files in the `js` directory within the source files path will be compilied using browserify, minified, and written to the specified output directory along with a sourcemap file.

## Images

Images in the `img` directory within the source files path will be losslessly optimized and output to the specified output directory.  The original input files will be preserved in the `img` directory, but be renamed to `{original-name}.processed.{original-extention}`.  If an already processed image needs to be updated, it can just be added into the `img` directory - the old, aleady processed, version will be removed and replaced.

Additionally there is a `image.revert` task which can be used to rename all "processed" images from within the source image folder.

## Gulpfile

The gulpfile contains the declaration for each task that can be run from the command line or used in other gulp tasks.  The tasks stored in `/gulp/tasks/` are included using `require()` statements for increased readability.  Each task is included and compound tasks are built using these tasks.

## Adding New Tasks
New tasks definitions are added in the 'gulp/tasks' directory.  The task must then be added to `gulpfile.js`.

The README file in `/gulp/` should be updated to include details of the new task.
