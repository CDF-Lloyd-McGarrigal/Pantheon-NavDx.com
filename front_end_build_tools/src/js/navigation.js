const hoverIntent = require( 'jquery-hoverintent' );

const uiService = require( './ui.js' );

const navService = {
	init: function(){

		this.hamburgerBTN = $('.hamburger');
		this.header = $('header#masthead');
		this.lnk = $('#site-menu .menu-item');
		this.isOpen = false;

		this.lnk.hoverIntent({
			over: function() {
				$(this).addClass('active');
			},
			out: function() {
				$(this).removeClass('active');
			},
			timeout: 250
		});

		// MOBILE ONLY : HAMBURGER BTN
		this.hamburgerBTN.on('click', function(){
			// toggle isOpen
			this.isOpen = !this.isOpen;
			if (this.isOpen) {
				navService.header.addClass('open-navigation');
				uiService.freezeScroll();
			} else {
				navService.header.removeClass('open-navigation');
				uiService.unFreezeScroll();
			}
		});
	}

}

module.exports = navService;
window.navService = navService;