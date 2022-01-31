var swiper = new Swiper('.blog-slider', {
      spaceBetween: 30,
      effect: 'fade',
      loop: false,
      mousewheel: {
        enabled: false,
        invert: false,
      },
      // autoHeight: true,
      pagination: {
        el: '.blog-slider__pagination',
        clickable: false
      },
      navigation: {
        nextEl: '.blog-slider__button'
      },
    });

    swiper.mousewheel.enabled == false;