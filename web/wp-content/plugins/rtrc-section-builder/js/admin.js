/**
 * This script handles displaying the "Edit Panel" link on the panel selector
 */

// Load jQuery into $
var $ = jQuery;

// Once the dom loads...
$(document).ready(function(){

	// Setup the links
	setupSectionLinks();

	// Then set up the listener.
	waitForNewSectionLinks();

	// Disable fields when not shown. Once immediately, hit it again after a tiny delay for extra stuff
	disableHiddenMetaboxFields();
	setTimeout(function(){
		disableHiddenMetaboxFields();
	}, 500);
		

	var changeTimeout;
	$( '#selected_template.rwmb-select' ).on( 'change', function(){
		$select = $( this );
		$select.prop( 'disabled', true );
		clearTimeout( changeTimeout );
		changeTimeout = setTimeout(function(){
			disableHiddenMetaboxFields();
			$select.prop( 'disabled', false );
		}, 1);
	});
});

/**
 * Set up all the panel links
 * @return void
 */
function setupSectionLinks()
{
	// Iterate through all existing jump to links
	$( '.rwmb-input .jumpToLink' ).each( function( $index ){

		// Set up the edit link as a jquery object
		var $editLink = $( this );

		// Find the associated panel selector
		var $panelSelector = $editLink.parents( '.rwmb-group-clone' ).find( 'select[id^=arteric_sections]' );

		// The the post id from the value
		var post_id = $panelSelector.val();

		// Change the edit link
		changeEditLink( $editLink, post_id );
	});

	// Set an action whenever a panel selector is changed
	$( 'select[id^=arteric_sections]' ).on( 'change', function(){

		// Set up the panel as a jquery object
		var $thisPanel = $( this );

		// Find the associated edit link
		var $editLink = $thisPanel.parents( '.rwmb-group-clone' ).find( '.rwmb-input .jumpToLink' );

		// Change the edit link
		changeEditLink( $editLink, $thisPanel.val() );
	});
}

/**
 * Changes the edit link given a post id and an object.
 * @param  jQuery Object $linkingObject The object to alter
 * @param  integer id             The ID to change to
 * @return void
 */
function changeEditLink( $linkingObject, id )
{

	// If the ID is not an empty string
	if( id != '' && typeof id != 'undefined' && id != null )
	{

		// Set the Link to that object
		$linkingObject.html( '<a target="_blank" href="' + rtrc_sections_admin_url.url + 'post.php?post=' + id + '&action=edit">Edit Section</a>')
	}
	else
	{
		// Blank it out
		$linkingObject.html( '' );
	}
}

/**
 * Waits for new panels to be added, and then adds the handlers above to them.
 * @return void
 */
function waitForNewSectionLinks()
{
	// Check how many items there currently are
	var count = $( '.rwmb-input .jumpToLink' ).length;

	// If we haven't set up the last count yet, set it
	if( typeof waitForNewSectionLinks.lastCount == 'undefined' )
	{
		waitForNewSectionLinks.lastCount = count;	
	}

	// If the current count doesn't match the established count
	if( count != waitForNewSectionLinks.lastCount )
	{
		// Check if we added links. If so, add the handlers
		if( waitForNewSectionLinks.lastCount < count )
		{
			setupSectionLinks();
		}

		// Then set the last count to the count
		waitForNewSectionLinks.lastCount = count;	
	}

	// Then half a second later, do it again.
	setTimeout( waitForNewSectionLinks, 1000 );
}

function disableHiddenMetaboxFields() {

	var $selectedSection = $('#selected_template').val();

	var $sectionId = false;
	if( $selectedSection != '' ){
		$sectionId = 'arteric-section-fields-' + $selectedSection;
	}

	// For each section...
	$( 'div[id^=arteric-section-fields]' ).each(function(){

		var $section = $(this);

		if( $section.attr( 'id' ) != $sectionId ){

			$section.find( '.rwmb-meta-box > input' ).prop( 'disabled', true );
			$section.find( '.rwmb-field' ).find( '.rwmb-input :input' ).prop( 'disabled', true );
		}
		else {
			
			$section.find( '.rwmb-meta-box > input' ).prop( 'disabled', false );
			$section.find( '.rwmb-field' ).find( '.rwmb-input :input' ).prop( 'disabled', false );	
		}
	});
}
