/*  Logo Principal se oculta al scrollear  */
$(window).scroll(function () {
    if ( $(this).scrollTop() > 300 || $(window).width() <= 768) {
      $(".logo").fadeOut("low");
    } else {
      $(".logo").fadeIn();
    }
  });