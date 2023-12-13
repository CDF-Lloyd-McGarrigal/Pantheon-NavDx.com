# CSS Utilties

The CSS utility library of the Front End Build Tools is a library that contains reusable and **immutable** styles, if you find you find yourself editing anything outside of the `config.scss` or `custom.scss` file - second guess what you're trying to achieve.

The purpose is to have a predictable, and reusable set of tools, used primarily for formatting, ad-hoc elements, and content entry.  The utilities are most beneficial during content entry and should not be used to construct the website layout, framework, or components.

## Quickstart and Examples

Spacing Size, Font Size, and Grid generation values can all be set in:

```
front-end-build-tools/sass/src/utilities
```

### Spacing Examples

```
.m0			// margin of 0 on all sides
.mb1		// margin-bottom of 0.5em (em step-value is set in config)
.m:mt0px	// On mobile, a margin-top of 0px 
.mb10px	// margin-bottom of 10px
```
*`m` for margin and `p` for padding*

### Font Size Examples

```
.fs18		// font-size of 18px, (set using em)
.m:fs14	// on mobile, font-size of 14ox (set using em)
```

### Grid

See [sassflexboxgrid.com](http://sassflexboxgrid.com/) for full usage *(the `.row` class has been replaced with `.grid-row` to be less generic)*

```
<div class="grid-row">
	<div class="col-xs-2">Two Columns</div>
	<div class="col-xs-10">Ten Columns</div>
</div>
```

### Other Utilities

All other utilities are defined in the following file and are single-purpose classes named exactly for what they do:

```
front-end-build-tools/sass/src/utilities/_utilities.scss
```

* Text Transforms (caps, small caps, etc)
* Font Weights
* Text Alignment
* Percentage Font Sizing
* Display (block, inline, none, etc)
* Colors
* Text Wrapping
* Clear
* Floats

## Configuration

The styles in the utility library are all reasonable defaults that should work across various sites, however there are a few things that can be configured if you find the library is not providing what you need.

The Utilities can be found in the following directory in the front end build tools the `config.scss` file is within the root of the utilities directory

```
front-end-build-tools/sass/src/utilities
```

### Spacing

Padding and Margin helper classes are generated based off of the values set in the configuration file.  Both padding and margin have `em` and `px` helper classes, the `em` classes are specific using a simple increment, for example:

```
.mb1 // a margin bottom of 0.5em
.mb2 // a margin bottom of 1em
```
Since the `em` sizing is relative to the base font size on site, an increment was used in naming instead of the absolute `em` value

For `px` spacing, the absolute pixel value is used in the helper class name:

```
.mb20px // a margin bottom of 20px
.mt5px // a margin top of 5px
```

All spacing starts at 0 and increments by the `increment` value and classes are generated based on this increment until the `end` value is reached.

### Font Sizing

The font size configuration is provided as a list, each value in the list will generate a font-size class, this way font sizes can be added and removed more easily.

All font sizes are translated to `em` when generating the helper classes, however the selectors are still named using the pixel value

```
.fs16		// a font-size of 16px
.m:fs12 	// on mobile, a font-size of 12px
```

### Grid

See [sassflexboxgrid.com](http://sassflexboxgrid.com/) for full usage *(the `.row` class has been replaced with `.grid-row` to be less generic)*

```
<div class="grid-row">
	<div class="col-xs-2">Two Columns</div>
	<div class="col-xs-10">Ten Columns</div>
</div>
```

## Other Helpers and Custom Helpers

### Other Utilities

All other utilities are defined in the following file and are single-purpose classes named exactly for what they do:

```
front-end-build-tools/sass/src/utilities/_utilities.scss
```

* Text Transforms (caps, small caps, etc)
* Font Weights
* Text Alignment
* Percentage Font Sizing
* Display (block, inline, none, etc)
* Colors
* Text Wrapping
* Clear
* Floats

### Custom Utilities

Custom utilities can be defined in the following file:

```
/front-end-build-tools/src/sass/utilities/_custom.scss
```

A few commented out examples are provided in this file - An example of a good candidate for a custom utility class would be font colors since they are specific to a site:

```
.colorBlue{
	color:$blue;
}
```

The utility library is imported *after* the `variables.scss` file so variables can be used