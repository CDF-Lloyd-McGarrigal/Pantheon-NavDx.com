=======
REQUIREMENTS:
=======
The following plugins need to be activated:
	- Meta Box, by metabox.io

The following Metabox.io extensions must be installed (this can be done using Meta Box AIO):
	- Meta Box Conditional Logic
	- Meta Box Group
	- Meta Box Include Exclude

=======
USAGE: 
=======

Registering Components:

	rtrc_register_component( $name, $fields, $template )
		- Name: The name of the field. This gets sanitized as a slug as the component ID.
		- Fields: The fields, as a Metabox.io fields array. Basically whatever you would pass to 'fields' when creating a metabox
		- Template: The file to load as the template for this component. See rtrc_cb_template_directory for changing the base directory to check for templates

		For example:
			rtrc_register_component( 'Content', array(

				// Body
				array(
				    'name'  => 'Body',
				    'id'    => 'body',
				    'type'  => 'wysiwyg',
				),

			), 'Content.php' );

Display components:

	rtrc_build_components( $id )
		- id: optional. Defaults to the ID of the current post.

Creating the templates:

	Create a file located in the template directory that matches the file name registered with "rtrc_register_component'. The default location is the root of the theme. For example, in the above example we need to create a Content.php file in the root of the theme. To change the template directory, see "rtrc_cb_template_driectory" filter below.

	The template has access to the $component variable that is an array of all the fields in the component. Any metadata that was not set in the CMS will be set to an empty string by default, to prevent any issues with array keys not being set.

	To see what data is within component, just drop the following at the top of the template file:

	<?php var_dump( $component ); ?>

	Remember that images are stored as arrays of IDs!

========
FILTERS:
========

rtrc_cb_template_directory
	- Changes the location that the plugin checks to find the template files
	- This is relative to the theme root, as it passes through "locate_template()"
	- Default: '/'
	- Accepts: String

rtrc_cb_allowed_posttypes
	- Changes the post types the component builder will display on
	- Default: [ 'page' ]
	- Accepts: array of strings

rtrc_cb_disallowed_templates
	- Sets up page templates that are not allowed to display the component builder
	- Default: []
	- Accepts: array of strings

rtrc_cb_metabox_filter
	- Allows altering of the component builder's metaboxes before it returns to the rwmb filter
	- Default: Metabox field array
	- Accepts: Metabox field array

rtrc_cb_wrapper_div_off
	- Determines if by default the wrapper div should go on each component
	- Default: false
	- Accepts: boolean

rtrc_cb_wrapper_div
	- Modifies the wrapper div that goes around each component
	- Default: '<div class="' . $output_classes . '">'
	- Arguements: 2
		- $component - the component
	- Accepts: string