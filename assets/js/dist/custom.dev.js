"use strict";

// Carousel Glide Config
var config = {
  type: 'carousel',
  perView: 3,
  autoplay: 2000 | true,
  gap: 0,
  breakpoints: {
    800: {
      perView: 1
    }
  }
};
new Glide('.glide', config).mount(); //   Mostrar logo pequeño al scrollear hacia arriba

$(window).scroll(function () {
  if ($(this).scrollTop() > 200) {
    $('#hiddenButton').fadeIn('fast');
  } else {
    $('#hiddenButton').fadeOut('fast');
  }
}); //   Newsletter

function doSubscribe() {
  var subscriberEmail = document.getElementById('subscriberEmail').value;
  var ajax = new XMLHttpRequest();
  ajax.open('POST', 'newsletter.php', true);
  ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  ajax.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('subscribe-message').innerHTML = 'Gracias por suscribirse.';
    }
  };

  ajax.send('subscriberEmail=' + subscriberEmail);
  return false;
} // Sticky Header


$(window).scroll(function () {
  if ($(this).scrollTop() > 0 || $(window).width() <= 768) {
    $('#header').addClass('sticky-top').fadeIn('slow'); //$('#header').addClass('fixed-top');
  } else {
    $('#header').removeClass('sticky-top');
  }
}); //   Caption Text

$(document).ready(function () {
  $('.caption-title-right').hide();
  $('.caption-title-right').fadeIn(2000);
});
$('#btn-slide-prev, #btn-slide-next').click(function () {
  if (this.id == 'btn-slide-next') {
    $('.caption-title-left').hide();
    $('.caption-title-left').fadeIn(1500);
  } else if (this.id == 'btn-slide-prev') {
    $('.caption-title-left').hide();
    $('.caption-title-left').fadeIn(1500);
  }
}); //   Superfish

jQuery(document).ready(function () {
  jQuery('ul.sf-menu').superfish();
}); // marca carousel

var root = document.documentElement;
var marqueeElementsDisplayed = getComputedStyle(root).getPropertyValue('--marquee-elements-displayed');
var marqueeContent = document.querySelector('ul.marquee-content');
root.style.setProperty('--marquee-elements', marqueeContent.children.length);

for (var i = 0; i < marqueeElementsDisplayed; i++) {
  marqueeContent.appendChild(marqueeContent.children[i].cloneNode(true));
} //button recordatorio


$(document).ready(function () {
  var pixelesArriba = 1500;
  $(window).on('scroll', function () {
    // Evento de Scroll
    if ($(window).scrollTop() + $(window).height() + pixelesArriba >= $(document).height()) {
      // Si estamos al final de la página
      $('.ocultar').stop(true).animate({
        // Escondemos el div
        opacity: 0
      }, 250);
    } else {
      // Si no
      $('.ocultar').stop(false).animate({
        // Mostramos el div
        opacity: 1
      }, 250);
    }
  });
});
/* Mostrar logo pequeño al scrollear hacia arriba */

$(window).scroll(function () {
  if ($(this).scrollTop() > 200) {
    $('#hiddenWhatsapp').fadeIn('fast');
  } else {
    $('#hiddenWhatsapp').fadeOut('fast');
  }
});