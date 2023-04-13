/* Mostrar logo pequeño al scrollear hacia arriba */
$(window).scroll(function () {
  if ($(this).scrollTop() > 200) {
    $('#hiddenButton').fadeIn("fast");
  } else {
    $('#hiddenButton').fadeOut("fast");
  }
});




