"use strict";

$(document).ready(function () {
  function showPopup() {
    $('.pop-up').addClass('show');
    $('.pop-up-wrap').addClass('show');
  }

  $('#close').click(function () {
    $('.pop-up').removeClass('show');
    $('.pop-up-wrap').removeClass('show');
  });
  setTimeout(showPopup, 8000);
}); //10500