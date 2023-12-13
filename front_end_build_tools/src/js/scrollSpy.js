const ui = require( './ui.js' );
const _ = require( '../../node_modules/lodash' );

module.exports = {
	init: function(){

		let self = this;
		this.$window = $(window);
        // Removed position relative because of tooltip bug....
        $( 'body' ).css( 'position', 'relative' ).scrollspy( {
				target: '.tertiary-nav-wrapper .sub-nav.current-menu-item li > a',
				offset: ui.getNavSize( false ) + 50
			}
		);
		
		this.preventNegative();
		this.setMobileMenu();

		/** A debounced scroll function */
		const debouncedScroll = _.throttle( () => {
			let windowTop = $(window).scrollTop();
			let firstTarget = $('.scrollSpy[id]:not([id=""]):first').offset().top;
			
			if(firstTarget > windowTop) {	
				self.preventNegative();
			}
		}, 50 );

		// call the debounced function on resize
		this.$window.scroll(function() {
			debouncedScroll();
		});

		$('body').on('activate.bs.scrollspy', this.setMobileMenu);
    },

	prep: function(){
        var $subMenu = $( '.tertiary-nav-wrapper .sub-nav.current-menu-item .item a, .mobile .indication-menu .sub-menu .menu-item a' );
		if ( $subMenu.length > 0 ) {
			$subMenu.each(function( index, val ) {
				const $el = $( val );
				let href = $el.attr( 'href' );
                href = href.substring( href.indexOf( '#' ), href.length );
                $el.attr( 'data-target', href + '-section' );
			});
		}
	},

	preventNegative: function(){
		
		// if the currenly active subnav doesn't have an active element
		if ( !$( '.tertiary-nav-wrapper .sub-nav.current-menu-item li' ).hasClass('active') ) {
			
			// select the first subnav element without the non-click class and set it to active
			$( '.tertiary-nav-wrapper .sub-nav.current-menu-item li.item:not(".non-click"):first' ).addClass( 'active' );

			
		}
	},

	setMobileMenu: function() {
		let activeItem = $('.sub-nav .item.active a').data('target');
			let activeMobileItem = $('.sub-menu li a.active');
			let newMobileItem = $('.current-menu-item .sub-menu li').find(`[data-target='${activeItem}']`)
			if(activeMobileItem.length > 0) {
				activeMobileItem.removeClass('active');
			}	
			newMobileItem.addClass('active');
	}

};
