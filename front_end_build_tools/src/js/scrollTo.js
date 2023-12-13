const ui = require( './ui.js' );
// const sectionModal = require('./sectionModal.js');
// const mobileMenu = require( './mobileMenu.js' );

module.exports = {
	
	extraOffset: 5,
	headerObj: {},

	/**
	 * Setup a listener for a hashchange and also see if there was a hash defined on pageload
	 * @author Rob Szpila <robert@arteric.com>
	 */
	init: function(headerObj) {

		this.headerObj = headerObj instanceof jQuery ? headerObj : $(headerObj);
		this.extraOffset = this.headerObj.outerHeight(true);

        // Excerpt from jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
        // jQuery.easing['swing'] = jQuery.easing['swing'];
        jQuery.extend( jQuery.easing,
        {
            easeInOutCubic: function (x, t, b, c, d) {
                if ((t/=d/2) < 1) return c/2*t*t*t + b;
                return c/2*((t-=2)*t*t + 2) + b;
            }
		});

		var self = this;
		var hash = location.hash;
		window.setTimeout(function() {
			if(hash.length){
				window.scrollTo(0, 0);
				
				window.showModal = false;

				let newTarget = $("[data-jumplink='" + hash.replace('#', '') +"']:visible");
				if(newTarget.length > 0){
					self.scrollTo( newTarget, false);
				}
			}
		}, 50);

		$(window).on('hashchange', function(){
			setTimeout(function(){
				var hash = location.hash;
				if(hash.length){
					let newTarget = $("[data-jumplink='" + hash.replace('#', '') +"']:visible");
					self.scrollTo( newTarget, false);
				}
			}, 100);
		});

		$( 'body' ).on( 'click', 'a[href*="#"]', function( e ) {
			let plus = false;
			const $this = $( this );
			var location = window.location;
			const href = $this.attr( 'href' );
			let hash = href.substring( href.indexOf( '#' ), href.length ).replace('#', '');
			let samePage = false;
			let linkpath = $this[0].pathname.replace(/\/+$/, '');
			let pathname = location.pathname.replace(/\/+$/, '');
	
			if(pathname.indexOf(linkpath) != -1) {
				samePage = true
			}

			if(e.target.className != "more-btn-link"){
				if($('body').hasClass('mobileMenuOpen')){
					$('body').removeClass('mobileMenuOpen');
				}

				if($('.more-btn').hasClass('active')){
					$('.more-btn').removeClass('active');
				}

				ui.unFreezeScroll();
				
			}
			if($this.parents('.js-isiAnchor').length > 0 || $this.hasClass('js-isiAnchor')) {
				return;
			}

			// If data-scroll is set to plus on the link, we'll account for the submenu
			if ( $this.data( 'scroll' ) == 'plus' ) {
				plus = true;
			}
			if ( self.hashInDOM( hash ) && samePage ) {
				e.preventDefault();
				
				window.showModal = false;
				
				if(ui.navIsOpen()) {
					ui.closeNav();
				}

				// self.scrollTo( self.$hashElement);
				window.setTimeout(function() {
					let newTarget = $("[data-jumplink='" + hash +"']");
					self.scrollTo( newTarget, false);	
				}, 100);

				self.scrollTo( self.$hashElement);
			}
		});
	},

	/**
	 * A wrapper function that first checks for a modal, then calls scrollToHash
	 * just an attempt to keep things a bit more DRY
	 * @param  {jQuery} $hashElement the DOM element to jump to
	 * @author Rob Szpila <robert@arteric.com>
	 */
	scrollToHashHelper: function( $hashElement, plus = false ) {
		// first check for open modals
		// Fetch all the modals on the page and set a flag
		var allModals = $.remodal.lookup;
		var isModalOpen = false;
		var self = this;
		// Loop through and check for any open ones, set the flag and close 'em
		allModals.forEach(function( aModal, index ) {
			if ( aModal.state == "opened" ) {
				// A modal is open, so on a close, let us fire our thing
				isModalOpen = true;
				$( document ).one( 'closed', '.remodal', function() {
					self.scrollToHash( $hashElement, plus );
				});
			}
		});
		// If there's no modal, just fire the scroll
		if ( !isModalOpen ) {
			self.scrollToHash( $hashElement, plus );
		}
	},

	/**
	 * Given an element (in this case a jumplink target) scroll to it and dont ask any qestions
	 * @param  {jQuery} $hashElement the DOM element to jump to
	 * @author Rob Szpila <robert@arteric.com>
	 */
	scrollToHash: function( $hashElement, plus ) {
		var headerTop = $('header').css('top');
		headerTop = parseInt(headerTop.replace('px', ''));

		var offset = $hashElement.offset().top - ui.getNavSize( plus ) + headerTop;
		// Calculate the distance and base the duration off of that (but cap it at 1 second for now)
		var distance = Math.abs( offset - $( window ).scrollTop() );
		// Arbitary values that feel right, 200ms per 500px of page to scroll
		var speed = 550 * ( distance / 500 );
		if ( speed > 2750 ) {
			speed = 2750;
		}

		$( 'html, body' ).animate({
			'scrollTop': offset
		},
		{
			'duration': speed,
            'easing': 'linear'
		}).promise().done(function(){
			//mobileMenu.closeInteriorDropdown()
		});
	},

    // a generic version of Rob's scroll mechanism where we can scroll to anything passed in, with an exposed callback
    // Jeremy McAllister, 5 Feb 2019
    scrollTo: function(selector, callback) {
        var $dest = selector instanceof jQuery ? selector : $(selector);
		if($dest.length == 0) { return false; }

		var headerTop = this.headerObj.css('top');
		headerTop = parseInt(headerTop.replace('px', ''));

		// var offset = $dest.offset().top - ui.getNavSize( false ) - headerTop - this.extraOffset;
		var offset = $dest.offset().top - headerTop - this.extraOffset;

        // Calculate the distance and base the duration off of that (but cap it at 1 second for now)
        var distance = Math.abs( offset - $( window ).scrollTop() );

        var speed = 220 * ( distance / 500 );
        if ( speed > 2750 ) {
            speed = 2750;
        }
        var didCallBack = false;

		$( 'html, body' ).animate({
			'scrollTop': offset
		},
		{
			'duration': speed,
			'easing': 'linear',
			complete: function() {

				if(didCallBack) {
					return;
				}
				if(!window.isiScroll) {
					if(window.triggerModal && !window.modalOpen) {
						// sectionModal.enterInterstitial();
					}
				} else {
					window.isiScroll = false;
				}
				window.showModal = true;

				if(typeof callback === "function" && callback){
					callback();
				}

				didCallBack = true;
				
				// This is kind of gross but since we are lazyloading images 
				// we need to make sure we are actually at the right location
				// If we aren't start scrolling again.
				// let bodyMargin = 0;
				// if(ui.isMobile || ui.isTablet) {
				// 	 bodyMargin = parseInt($('body').css('margin-top'), 10) || 0;
				// }
				// let newOffset = $dest.offset().top  - ui.getNavSize( false ) + headerTop + bodyMargin;
				// if($(window).scrollTop() != newOffset) {
					
				// 	$( 'html, body' ).animate({
				// 		'scrollTop': newOffset,
				// 	},
				// 	{
				// 		'easing': 'linear',
				// 		'speed': speed
				// 	});	
				// }
			}	
		}
		);
    },

	/**
	 * Check the DOM for a hash, if there isnt one, it's probably a modal or something
	 * @param  {string}		hash the hash from the URL
	 * @return {bool}		if there hash exists in the DOM or not
	 */
	hashInDOM: function( hash ) {
		var haveHash = false;
		this.$hashElement = $("[data-jumplink='" + hash +"']");
		if ( this.$hashElement.length > 0 ) {
			haveHash = true;
		}
		return haveHash;
	}
};
