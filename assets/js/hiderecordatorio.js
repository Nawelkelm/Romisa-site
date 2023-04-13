//button recordatorio


$(document).ready(function() {
  var pixelesArriba = 900;
  $(window).on('scroll', function() { // Evento de Scroll
    if (($(window).scrollTop() + $(window).height() + pixelesArriba) >= $(document).height()) { // Si estamos al final de la página
      $('.ocultar').stop(true).animate({ // Escondemos el div
        opacity: 0
      }, 250);
    } else { // Si no
      $('.ocultar').stop(false).animate({ // Mostramos el div
        opacity: 1
      }, 250);
    }
  });
});
