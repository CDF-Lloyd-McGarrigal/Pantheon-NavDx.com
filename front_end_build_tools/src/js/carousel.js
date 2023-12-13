const slick = require ('slick-carousel');

const carouselService = {

    init: function(){
        $('.slick-slider').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            rows:1,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 3667
        });
    }

}

module.exports = carouselService;
window.carouselService = carouselService;