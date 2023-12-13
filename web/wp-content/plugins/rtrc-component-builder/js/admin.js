var componentDisableHiddenMetaboxFields = function() {

	var $this = jQuery( this );

	if( !$this.is(":visible") ){
		
		$this.find( '.rwmb-input :input' ).prop( 'disabled', true );
	}
	else {
		
		$this.find( '.rwmb-input :input' ).prop( 'disabled', false );
	}
}

jQuery(document).on( 'change', '.js-componentSelector select', function(){
	
	var $this = jQuery( this );
	var component = $this.val();
	var $group = $this.parents( '.rwmb-group-clone' );

	$group.find( '.rtrcComponent' ).hide();

	if( !component == '' ){
		$group.find( '.rtrcComponent-' + component ).show();
	}

	$group.find( '.rtrcComponent' ).each(componentDisableHiddenMetaboxFields)
});

jQuery(document).ready(function(){

	setTimeout(function(){
		jQuery( '.js-componentSelector select' ).change()
	}, 1000);
});