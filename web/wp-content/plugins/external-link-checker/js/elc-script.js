/**
* JROX October 22, 2016
* Sets up the jQuery Selector that looks for all external links minus any excluded links from the setting page.
*/
var $ = jQuery;

var elcHandler = {

	exclude: {},
	curHost: '',
	externalSelector: '',
	wrapperExcludeNoTrigger: ':not(.js-noTriggerExitModal a)',
	excludeNoTrigger: ':not(.js-noTriggerExitModal)',
	forcedSelector: '.js-forceModal',
	excludeForced: ':not(.js-forceModal)',

	init: function(){

		// First, lets establish some basics
		// The default modal id is "exitmodal"
		if( typeof this.exclude.default_modal_id == 'undefined' || this.exclude.default_modal_id == '' ){
			this.exclude.default_modal_id = 'exitmodal';
		}

		// The current host
		this.curHost = window.location.hostname;
		
		//Select all href that start with http but not ones that contain the current host.		
		this.externalSelector = "a[href^='http']:not([href*='" + this.curHost + "'])" + this.wrapperExcludeNoTrigger + this.excludeNoTrigger + this.excludeForced;

		// Attach the click events
		$(document).on('click', this.externalSelector, { self: this }, this.clickHandler);
		$(document).on('click', this.forcedSelector, { self: this }, this.forcedClickHandler);
	},

	clickHandler: function( event ){

		// Get the "self" object
		var self = event.data.self;

		// Get the element
		var element = event.currentTarget;

		// Make sure we have a hostname to continue
		if( typeof element.hostname === 'undefined' ){

			return;
		}

		// Get the domain (hostname)
		var domain = element.hostname.replace( /^www./, '' );	

		// If excluded, bail
		if( self.isExcludedLink( element ) ){
			return true;
		}

		// If has specific modal, use it
		if( self.isSpecificModal( event, element, domain ) ){

			return true;
		}

		// If this domain is explicitly excluded, dont go any further
		if( self.isExcludedDomain( element, domain ) ){

			return true;
		}

		// If this URL is explicitly excluded, dont go any further
		if( self.isExcludedURL( element ) ){

			return true;
		}

		// If we got through all of that, send the on click event
		event.preventDefault();
		event.data = { modalID: self.exclude.default_modal_id, modalContent: self.exclude.default_modal_content };
		self.onClickEvent( event );
	},

	forcedClickHandler: function( event ){

		// Get the "self" object
		var self = event.data.self;

		// Get the element
		var element = event.currentTarget;
		var $element = $(element);

		// Make sure we have a hostname to continue
		if( typeof element.hostname === 'undefined' ){

			return;
		}

		// Get the domain (hostname)
		var domain = element.hostname.replace( /^www./, '' );

		// Get the modal ID, if we have one
		var modalID = $element.attr( 'data-modal-id' );

		// If we don't, get the default
		if( !modalID ){

			modalID = self.exclude.default_modal_id;
		}

		// Get the modal content, if we have one
		var modalContent = $element.attr( 'data-modal-content' );

		// If we don't, get the default
		if( !modalContent ){

			modalContent = self.exclude.default_modal_content;
		}

		// Attach the click events
		event.preventDefault();
		event.data = { modalID: modalID, modalContent: modalContent };
		self.onClickEvent( event );
	},

	isExcludedLink: function( element ){

		// A link is excluded if it has the exclude class...
		var hasClass = $(element).hasClass( '.js-noTriggerExitModal' );

		// Or has a parent with that class.
		var hasParent = ( $(element).parents( '.js-noTriggerExitModal' ).length > 0 );

		return hasClass || hasParent;
	},

	isSpecificModal: function( event, element, domain ){

		// Check if the modal triggers are an array
		if( Array.isArray( this.exclude.specific_modal_triggers ) ){
			for( var index = 0; index < this.exclude.specific_modal_triggers.length; index++ ){
				
				var trigger = this.exclude.specific_modal_triggers[index];

				// If we don't have an ID for this modal, use the default one
				if( typeof trigger.modal == 'undefined' && typeof this.exclude.default_modal_id !== 'undefined' ){

					trigger.modal = this.exclude.default_modal_id;
				}

				if( typeof trigger.modal !== 'undefined' && typeof trigger.domains !== 'undefined' ){

					// Sanity check - If we don't have content, get the default
					if( typeof trigger.content == 'undefined' ){
						trigger.content = this.exclude.default_modal_content;
					}

					// Get the domains
					var domainArray = trigger.domains;
					var urlArray = trigger.urls;

					// If this exact URL or domain is in our array, do that one
					if( domainArray.indexOf( domain ) > -1 || urlArray.indexOf( element.href ) > -1 ){

						event.data = { modalID: trigger.modal, modalContent: trigger.content }
						this.onClickEvent( event );
						return true;
					}
				}
			}
		}

		return false;
	},

	isExcludedDomain: function( element, domain ){

		//Json object passed from the setting page, array of Urls to exclude.
		var excludeDomains = this.exclude.excluded_domains;

		if( excludeDomains.indexOf('*') > -1 ){
			return true;
		}

		// Return if our domain is in there
		return excludeDomains.indexOf( domain ) > -1;
	},

	isExcludedURL: function( element ){

		//Json object passed from the setting page, array of Urls to exclude.
		var excludeURLs = this.exclude.excluded_urls;

		// Return if our domain is in there
		return excludeURLs.indexOf( element.href ) > -1;
	},

	onClickEvent: function(event){

		event.preventDefault();

		//Pass the Url of the link clicked to the custom trigger.
		var theExternalLink = $(event.currentTarget);

		// Custom trigger can by called by .on('externalLink',function(event, triggerLink){})
		// triggerLink would be the url of this click.
		$(document).trigger('externalLink', [
			theExternalLink,
			event.data.modalID,
			event.data.modalContent
		]);

	}
}

elcHandler.exclude = window.exclude;
$( document ).ready(elcHandler.init());
