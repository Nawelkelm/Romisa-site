var anchoVentana = window.innerWidth;

const funcion1 = () => {
  $(document).ready(function () {
    // Change background image of a div by mouseover on the box
    $('.box').mouseover(function () {
      $('.boxi').show(200);
      var display = 'none';
      $('.box').css('display', display);
      //var display = "block";
      //     $(".boxi").css("display", display);
    });

    $('.boxi').mouseleave(function () {
      var display = 'none';
      $('.boxi').css('display', display);
      //$(".boxi").hide(1000);
    });
    $('.boxi').mouseleave(function () {
      var display = 'contents';
      $('.box').css('display', display);
    });

    $(document).ready(function () {
      $('.box2').mouseover(function () {
        var display = 'none';
        //$(".box3").css("display", display);
        $('.box3').show(200);
      });
      $('.box2').mouseover(function () {
        var display = 'none';
        var display2 = 'contents';
        $('.box2').css('display', display);
        $('.boxi').css('display', display);
        $('.box').css('display', display2);
      });
      $('.box3').mouseleave(function () {
        var display = 'contents';
        $('.box2').css('display', display);
      });
      $('.box3').mouseleave(function () {
        var display = 'none';
        $('.box3').css('display', display);
        //  $(".box3").hide(1000);
      });
    });
    $('.box4').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box5').show(200);
      // $(".box5").css("display", display);
      $('.box4').css('display', display2);
      $('.box3').css('display2', display);
      // $(".box5").slideUp(2000);
    });
    $('.box5').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box4').css('display', display);
      $('.box5').css('display', display2);
    });
    $('.box6').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box7').show(200);
      // $(".box5").css("display", display);
      $('.box6').css('display', display2);
      $('.box5').css('display2', display);
      // $(".box5").slideUp(2000);
    });
    $('.box7').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box6').css('display', display);
      $('.box7').css('display', display2);
    });
    $('.box8').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box9').show(200);
      // $(".box5").css("display", display);
      $('.box8').css('display', display2);
      $('.box7').css('display2', display);
      // $(".box5").slideUp(2000);
    });
    $('.box9').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box8').css('display', display);
      $('.box9').css('display', display2);
    });
    $('.box10').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box11').show(200);
      // $(".box5").css("display", display);
      $('.box10').css('display', display2);
      $('.box9').css('display2', display);
      // $(".box5").slideUp(2000);
    });
    $('.box11').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box10').css('display', display);
      $('.box11').css('display', display2);
    });
    $('.box12').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box13').show(200);
      // $(".box5").css("display", display);
      $('.box12').css('display', display2);
      $('.box11').css('display2', display);
      // $(".box5").slideUp(2000);
    });

    $('.box13').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box12').css('display', display);
      $('.box13').css('display', display2);
    });
    $('.box14').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box15').show(200);
      // $(".box5").css("display", display);
      $('.box14').css('display', display2);
      $('.box13').css('display2', display);
      // $(".box5").slideUp(2000);
    });
    $('.box15').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box14').css('display', display);
      $('.box15').css('display', display2);
    });
    $('.box16').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box17').show(200);
      // $(".box5").css("display", display);
      $('.box16').css('display', display2);
      $('.box15').css('display2', display);
      // $(".box5").slideUp(2000);
    });
    $('.box17').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box16').css('display', display);
      $('.box17').css('display', display2);
    });
    $('.box18').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box19').show(200);
      // $(".box5").css("display", display);
      $('.box18').css('display', display2);
      $('.box17').css('display2', display);
      // $(".box5").slideUp(2000);
    });
    $('.box19').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box18').css('display', display);
      $('.box19').css('display', display2);
    });
    $('.box20').mouseover(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box21').show(200);
      // $(".box5").css("display", display);
      $('.box20').css('display', display2);
      $('.box19').css('display2', display);
      // $(".box5").slideUp(2000);
    });
    $('.box21').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box20').css('display', display);
      $('.box21').css('display', display2);
    });
  });
};
const funcion2 = () => {
  alert('Que tenga un Excelente día!');
};
const funcion3 = () => {
  $(document).ready(function () {
    // Change background image of a div by mouseover on the box
    $('.box').mouseover(function () {
      var display = 'none';
      $('.boxi').css('display', display);
    });
    $('.box').mouseover(function () {
      var display = 'contents';
      $('.box').css('display', display);
    });
    $('.boxi').mouseleave(function () {
      var display = 'none';
      $('.boxi').css('display', display);
    });
    $('.boxi').mouseleave(function () {
      var display = 'contents';
      $('.box').css('display', display);
    });

    $(document).ready(function () {
      $('.box2').mouseover(function () {
        var display = 'none';

        $('.box3').css('display', display);
      });
      $('.box2').mouseover(function () {
        var display = 'contents';
        $('.box2').css('display', display);
      });

      $('.box3').mouseleave(function () {
        var display = 'contents';

        $('.box2').css('display', display);
      });

      $('.box3').mouseleave(function () {
        var display = 'none';
        $('.box3').css('display', display);
      });
    });
    $('.box4').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box5').css('display', display);
      $('.box4').css('display', display2);
    });

    $('.box5').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box4').css('display', display);
      $('.box5').css('display', display2);
    });
    $('.box6').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box7').css('display', display);
      $('.box6').css('display', display2);
    });

    $('.box7').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box6').css('display', display);
      $('.box7').css('display', display2);
    });
    $('.box8').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box9').css('display', display);
      $('.box8').css('display', display2);
    });

    $('.box9').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box8').css('display', display);
      $('.box9').css('display', display2);
    });
    $('.box10').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box11').css('display', display);
      $('.box10').css('display', display2);
    });

    $('.box11').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box10').css('display', display);
      $('.box11').css('display', display2);
    });
    $('.box12').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box13').css('display', display);
      $('.box12').css('display', display2);
    });

    $('.box13').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box12').css('display', display);
      $('.box13').css('display', display2);
    });
    $('.box14').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box15').css('display', display);
      $('.box14').css('display', display2);
    });

    $('.box15').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box14').css('display', display);
      $('.box15').css('display', display2);
    });
    $('.box16').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box17').css('display', display);
      $('.box16').css('display', display2);
    });

    $('.box17').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box16').css('display', display);
      $('.box17').css('display', display2);
    });
    $('.box18').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box19').css('display', display);
      $('.box18').css('display', display2);
    });

    $('.box19').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box18').css('display', display);
      $('.box19').css('display', display2);
    });
    $('.box20').mouseover(function () {
      var display = 'none';
      var display2 = 'contents';
      $('.box21').css('display', display);
      $('.box20').css('display', display2);
    });

    $('.box21').mouseleave(function () {
      var display = 'contents';
      var display2 = 'none';
      $('.box20').css('display', display);
      $('.box21').css('display', display2);
    });
  });
};

window.onresize = function () {
  anchoVentana = window.innerWidth;
  console.log(anchoVentana);
};

if (anchoVentana > 1000) {
  funcion1();
} else if (anchoVentana > 750 && anchoVentana < 1000) {
  funcion2();
} else {
  funcion3();
}
