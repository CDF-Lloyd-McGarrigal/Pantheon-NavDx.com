const _ = require( 'lodash' );

module.exports = {
	/**
	 * Use this for global UI events, calulations etc
	 */
	init: function() {
		// Set up some variables
		this.$window = $( window );
		this.$body = $( 'body' );
		this.mobileMediaQuery = "( max-width: 767px )";
		this.tabletMediaQuery = "( max-width: 1023px )";
		this.isMobile = false;
		this.isTablet = false;
		this.tabletClass = 'isTablet';
		this.mobileClass = 'isMobile';
		this.touchInputClass = 'touchInput';
		this.freeze = false;
		this.scrollTop = 0;

		// Get the display size
		this.displaySize();
		// Detect touch y'all
		this.detectTouch();

		/** A debounced resize function */
		const debouncedResize = _.throttle( () => {
			this.resize();
		}, 50 );
		// call the debounced function on resize
		this.$window.resize(function() {
			debouncedResize();
		});

		/** A debounced scroll function */
		const debouncedScroll = _.throttle( () => {
			this.scroll();
		}, 50 );
		// call the debounced function on resize
		this.$window.scroll(function() {
			debouncedScroll();
		});
	},

	/**
	 * Resize triggers go here, this should be debounced in init
	 */
	resize: function() {
		// Detect if we've hit a display threshold (to/from destop/mobile) and detect the actual size
		this.displayDetection();
	},

	/**
	 * Scroll triggers go here, this should be debounced in init
	 */
	scroll: function() {
		// Probably ISI and sticky header stuff, once we make those
	},

	/**
	 * Detects a touch event and add a class to the body, used in conjunction with screen size
	 * detection, we should be able to infer mobile devices
	 * @author Rob Szpila <robert@arteric.com>
	 */
	detectTouch: function() {
		/** A touch event listener that removes itself */
		$( document ).one( 'touchstart', () => {
			this.$body.addClass( this.touchInputClass );
		});
	},

	/**
	 * Using handy matchMeda we can be super accurate in JS about media queries...horray for parity
	 * called from displayDetection on resize
	 * @author Rob Szpila <robert@arteric.com>
	 */
	displaySize: function() {
		if ( window.matchMedia( this.tabletMediaQuery ).matches ) {
			// We're totally not mobile...probably
			this.isMobile = false;
			this.$body.removeClass( this.mobileClass );
			// We're TABLET!
			this.isTablet = true;
			this.$body.addClass( this.tabletClass );
		}
		if ( window.matchMedia( this.mobileMediaQuery ).matches ) {
			// We're totally not tablet...definitely
			this.isTablet = false;
			this.$body.removeClass( this.tabletClass );
			// We're mobile!
			this.isMobile = true;
			this.$body.addClass( this.mobileClass );
		}
		if ( !window.matchMedia( this.mobileMediaQuery ).matches && !window.matchMedia( this.tabletMediaQuery ).matches ) {
			// We're nothing
			this.isTablet = false;
			this.isMobile = false;
			this.$body.removeClass( this.mobileClass ).removeClass( this.tabletClass );
		}
	},

	/**
	 * Emit a thing when we cross display thresholds
	 */
	displayDetection: function() {
		// Before we determine our size now, were we a larger size before?
		var wasLargeView = ( !this.isTablet && !this.isMobile );

		// Get the display size
		this.displaySize();

		// If we just changed down to tablet or mobile, lets get the user to where they were
		if (  wasLargeView && ( this.isTablet || this.isMobile ) ) {
			// Let's emit an event, because this is handy
			$( document ).trigger( 'sizedToMobile' );
		}
		// If we just changed up, emit an event
		if ( !wasLargeView && ( !this.isTablet && !this.isMobile ) ) {
			$( document ).trigger( 'sizedToDesktop' );
		}
	},

	/**
	 * Check if we're at tablet or lower and set the body to fixed and preserve scroll
	 * @author Rob Szpila <robert@arteric.com>
	 */
	freezeScroll: function() {
		this.scrollTop = $( window ).scrollTop();
		this.$body.addClass( 'fixed' );
		this.$body.css( 'top', this.scrollTop * -1 );
	},

	/**
	 * Check if the scroll is frozen and unfreeze
	 * @author Rob Szpila <robert@arteric.com>
	 */
	unFreezeScroll: function() {
		if ( this.$body.hasClass( 'fixed' ) ) {
			this.$body.removeClass( 'fixed' );
			this.$body.css( 'top', 'auto' );
			$( window ).scrollTop( this.scrollTop );
		}
	},

	navIsOpen: () => {
		return false;
	}
};
