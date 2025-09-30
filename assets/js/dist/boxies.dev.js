"use strict";

var anchoVentana = window.innerWidth;
var display;

var funcion1 = function funcion1() {
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
  boxes.forEach(function (box) {
    document.querySelector;
    document.querySelector(box.box).addEventListener('mouseover', function () {
      document.querySelector(box.box).style.display = 'none';
      document.querySelector(box.boxi).style.display = 'block';
    });
    document.querySelector(box.boxi).addEventListener('mouseleave', function () {
      document.querySelector(box.boxi).style.display = 'none';
      document.querySelector(box.box).style.display = 'block';
    });
  });
};

var funcion2 = function funcion2() {
  alert('Que tenga un Excelente día!');
};

var funcion3 = function funcion3() {
  var boxes = Array.from(document.getElementsByClassName('box'));
  var boxis = Array.from(document.getElementsByClassName('boxi'));
  var boxElements = [];

  for (var i = 2; i <= 21; i++) {
    boxElements.push(document.getElementsByClassName("box".concat(i))[0]);
  }

  boxes.forEach(function (box) {
    box.addEventListener('mouseover', function () {
      boxis.forEach(function (boxi) {
        boxi.style.display = 'none';
      });
      box.style.display = 'contents';
    });
  });
  boxis.forEach(function (boxi) {
    boxi.addEventListener('mouseleave', function () {
      boxis.forEach(function (boxi) {
        boxi.style.display = 'contents';
      });
      boxes.forEach(function (box) {
        box.style.display = 'none';
      });
    });
  });
  boxElements.forEach(function (boxElement, index) {
    boxElement.addEventListener('mouseover', function () {
      var nextBoxElement = boxElements[index + 1];
      var currentBoxElement = boxElements[index];
      nextBoxElement.style.display = 'none';
      currentBoxElement.style.display = 'contents';
    });
    boxElement.addEventListener('mouseleave', function () {
      var nextBoxElement = boxElements[index + 1];
      var currentBoxElement = boxElements[index];
      currentBoxElement.style.display = 'contents';
      nextBoxElement.style.display = 'none';
    });
  });
};

window.onresize = function () {
  anchoVentana = window.innerWidth;
  console.log(anchoVentana);
};

switch (true) {
  case anchoVentana > 1000:
    funcion1();
    break;

  case anchoVentana > 750 && anchoVentana < 1000:
    funcion2();
    break;

  default:
    funcion3();
    break;
}