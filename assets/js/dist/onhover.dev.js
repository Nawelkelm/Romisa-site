"use strict";

var anchoVentana = window.innerWidth;
var display;

var funcion1 = function funcion1() {
  $(document).ready(function () {
    var boxes = [{
      box: '.box',
      boxi: '.boxi'
    }, {
      box: '.box2',
      boxi: '.box3'
    }, {
      box: '.box4',
      boxi: '.box5'
    }, {
      box: '.box6',
      boxi: '.box7'
    }, {
      box: '.box8',
      boxi: '.box9'
    }, {
      box: '.box10',
      boxi: '.box11'
    }, {
      box: '.box12',
      boxi: '.box13'
    }, {
      box: '.box14',
      boxi: '.box15'
    }, {
      box: '.box16',
      boxi: '.box17'
    }, {
      box: '.box18',
      boxi: '.box19'
    }, {
      box: '.box20',
      boxi: '.box21'
    }];
    boxes.forEach(function (_ref) {
      var box = _ref.box,
          boxi = _ref.boxi;
      $(box).mouseover(function () {
        display = 'none';
        $(box).css('display', display);
        display = 'contents';
        $(boxi).css('display', display);
      });
      $(boxi).mouseleave(function () {
        display = 'none';
        $(boxi).css('display', display);
        display = 'contents';
        $(box).css('display', display);
      });
    });
  });
};

var funcion2 = function funcion2() {
  alert('Que tenga un Excelente día!');
};

var changeDisplay = function changeDisplay(element, target, display) {
  $(element).mouseover(function () {
    $(target).css('display', 'none');
    $(element).css('display', display);
  });
  $(target).mouseleave(function () {
    $(element).css('display', 'contents');
    $(target).css('display', 'none');
  });
};

var funcion3 = function funcion3() {
  $(document).ready(function () {
    changeDisplay('.box', '.boxi', 'contents');
    changeDisplay('.box2', '.box3', 'contents');
    changeDisplay('.box4', '.box5', 'contents');
    changeDisplay('.box6', '.box7', 'contents');
    changeDisplay('.box8', '.box9', 'contents');
    changeDisplay('.box10', '.box11', 'contents');
    changeDisplay('.box12', '.box13', 'contents');
    changeDisplay('.box14', '.box15', 'contents');
    changeDisplay('.box16', '.box17', 'contents');
    changeDisplay('.box18', '.box19', 'contents');
    changeDisplay('.box20', '.box21', 'contents');
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