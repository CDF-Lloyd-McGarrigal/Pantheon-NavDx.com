const ui = require( './ui.js' );
const carousel = require( './carousel.js' );
const navigation = require( './navigation.js' );
const scrollTo = require('./scrollTo');
const Modals = require('./modals');
const VideoPlayer = require('./videoPlayer');

$( document ).ready(function() {
	ui.init();
	carousel.init();
	navigation.init();
	scrollTo.init($('#masthead'));

	let modals = new Modals();
	modals.init();
	if($('[data-modal-trigger]').length > 0){
		let triggers = $('[data-modal-trigger]');
		triggers.each((idx, elem) => {
			modals.watchObject($(elem).find('input[type="submit"]'), $(elem).attr('data-modal-trigger'));
		});
	}

	if($('#video-player').length > 0){
		let video = new VideoPlayer();
		video.init( $('.video') );
	}

    /**
     * Apparently Gravity Forms does a full request/refresh to
     * validate required fields; this isn't ideal because our
     * form is a modal. So we check for the existence of
     * validation error messages and pop the modal up again.
     */
	function clientValidation(){
        if(
			   $('section[data-remodal-id="registration-modal"] .validation_message').length > 0
			|| $('section[data-remodal-id="registration-modal"] .gform_confirmation_message').length > 0
		){
            modals.openModal($('[data-remodal-id="registration-modal"]'));
        }
    }
	clientValidation();

});
