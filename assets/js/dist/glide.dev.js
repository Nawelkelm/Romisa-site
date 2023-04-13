"use strict";

/* Carousel Glide Config*/
var config = {
  type: 'carousel',
  perView: 4,
  autoplay: 2000 | true,
  gap: 0,
  breakpoints: {
    800: {
      perView: 1
    }
  }
};
new Glide('.glide', config).mount();