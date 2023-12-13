const ui = require('./ui');

require('remodal');

class Modals{
    init(){
        $('body').on('click', 'a', event => {
            let url = new URL(event.currentTarget);
            let modal = this.selectModal(url.hash);
            this.openModal(modal, event);
        });
        $(document).on('opening', '.modal-popup', () => {
            ui.freezeScroll();
        });
        $(document).on('closing', '.modal-popup', () => {
            ui.unFreezeScroll();
        });
    }

    selectModal(selector){
        return $('[data-remodal-id="' + selector.replace('#', '') + '"]');
    }

    openModal(modal, event){
        if(modal.length > 0){
            if(typeof event !== 'undefined'){
                event.preventDefault();
            }
            let modalObject = modal.remodal({
                hashTracking: false,
                closeOnEscape: true,
                closeOnOutsideClick: true,
            });
            modalObject.open();
        }
    }

    /**
     * Handle a specific modal trigger that may not be an anchor tag
     * @param {Element} selector Object that gets the click event
     * @param {String} id ID of the modal to open
     */
    watchObject(selector, id){
        if(!selector instanceof jQuery){
            selector = $(selector);
        }
        selector.on('click', event => {
            let modal = this.selectModal(id);
            this.openModal(modal, event);
        });
    }
}

module.exports = Modals;