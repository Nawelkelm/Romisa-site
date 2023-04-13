/* Mostrar logo pequeño al scrollear hacia arriba */
$(window).scroll(function () {
    if ($(this).scrollTop() > 200) {
      $('#hiddenWhatsapp').fadeIn("fast");
    } else {
      $('#hiddenWhatsapp').fadeOut("fast");
    }
  });
  