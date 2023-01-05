jQuery( document ).ready(function(){

    var interleaveOffset = 0.5;
    var swiperOptions = {
        loop: true,
        speed: parseInt( bizberg_object.ab_slider_speed ),
        grabCursor: true,
        mousewheelControl: true,
        keyboardControl: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
        autoplay: {
            delay: parseInt( bizberg_object.slider_autoplay_delay ),
        },
    };

    var swiper = new Swiper(".swiper-container-blog", swiperOptions);
    
});