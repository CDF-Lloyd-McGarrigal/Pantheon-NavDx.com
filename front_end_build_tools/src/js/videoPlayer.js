/**
 * There was a conflict in https://github.com/feross/yt-player that
 * apparently kept it from working with our current setup, so I
 * downloaded it and modified it. Find it in the /vendor
 * directory.
 */
const ui = require('./ui');
const YTPlayer = require('./vendor/yt-player');

class VideoPlayer{
    /**
     * 
     * @param {jQuery object or objects} els Video triggers
     */
    init(els){
        /**
         * Set up the player with some sane defaults
         */
        let options = {
            autoplay: true,
            keyboard: false,
            modestBranding: true,
            host: 'https://www.youtube-nocookie.com',
            related: false,
        };
        if(ui.isMobile){
            options = Object.assign(options, {
                height: '100%',
                width: '100%',
            });
        }
        this.player = new YTPlayer('#video-player', options);
        /**
         * Handle the triggers
         */
        els.on('click', e => {
            e.preventDefault();
            this.player.load($(e.currentTarget).attr('data-video-id'), true);
        });

        $(document).on('closing', '.modal-popup', () => {
            if(this.player.getState() == 'playing'){
                this.player.stop();
            }
        });

    }
}

module.exports = VideoPlayer;