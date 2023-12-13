=======
USAGE: 
=======

Registering sections:

	rtrc_register_section( $name, $fields, $template )
		- Name: The name of the field. This gets sanitized as a slug as the section ID.
		- Fields: The fields, as a Metabox.io fields array. Basically whatever you would pass to 'fields' when creating a metabox
		- Template: The file to load as the template for this component. See rtrc_cb_template_directory for changing the base directory to check for templates

		For example:
			rtrc_register_section( 'Content', array(

				// Body
				array(
				    'name'  => 'Body',
				    'id'    => 'body',
				    'type'  => 'wysiwyg',
				),

			), 'Content.php' );

		- If you want to use tabs, then create a "tab" entry for that field with the name of the tab. Use "_" in the place of spaces.

Using the sections:

	Call rtrc_build_sections() on the template where you want to use the registered sections
	- Optional: Give the section builder an ID to use the sections of that post ID

	Call rtrc_render_section( <SECTION ID> ) to render a specific section
	- This should only be used when you are specifically trying to output one section. If you're outputting all sections on apost, use rtrc_build_sections() instead

Creating the templates:

	Create a file located in the template directory that matches the file name registered with "rtrc_register_section'. The default location is the root of the theme. For example, in the above example we need to create a Content.php file in the root of the theme. To change the template directory, see "rtrc_sections_template_driectory" filter below.

	The template has access to the $section variable that is an array of all the fields in the section. Any metadata that was not set in the CMS will be set to an empty string by default, to prevent any issues with array keys not being set.

	To see what data is within section, just drop the following at the top of the template file:

	<?php var_dump( $section ); ?>

	Remember that images are stored as arrays of IDs!

========
FILTERS:
========

rtrc_sections_template_directory
	- Changes the location that the plugin checks to find the template files
	- This is relative to the theme root, as it passes through "locate_template()"
	- Default: '/'
	- Accepts: String

rtrc_sections_allowed_posttypes
	- Changes the post types the section builder will display on
	- Default: [ 'page' ]
	- Accepts: array of strings

rtrc_sections_disallowed_templates
	- Sets up page templates that are not allowed to display the section builder
	- Default: []
	- Accepts: array of strings

rtrc_sections_metabox_filter
	- Allows altering of the section builder's metaboxes before it returns to the rwmb filter
	- Default: Metabox field array
	- Accepts: Metabox field array

rtrc_sections_section_fields
	- Allows altering of the fields on the section field
	- Default: [ 'section_selector', 'jump_link' ] metabox definitions
	- Accepts: Any array of metabox fields

rtrc_sections_section_supports
	- Defines what the section post type supports
	- Default: [ 'title', 'editor' ]
	- Accepts: An array of supportable elements for a post type
	