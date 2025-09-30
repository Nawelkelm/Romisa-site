"use strict";

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _construct(Parent, args, Class) { if (isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

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
  var subscriberEmail = document.getElementById('subscriberEmail').value; // Suggestion 2: Add basic email validation before sending the request

  if (!validateEmail(subscriberEmail)) {
    document.getElementById('subscribe-message').innerHTML = 'Please enter a valid email address.';
    return false;
  }

  var ajax = new XMLHttpRequest();
  ajax.open('POST', 'newsletter.php', true);
  ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  ajax.onreadystatechange = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        document.getElementById('subscribe-message').innerHTML = 'Gracias por suscribirse, para más detalles contactese a nuestro whatsapp de ventas.';
      } else {
        // Suggestion 1: Add error handling in the 'onreadystatechange' callback to handle potential errors in the AJAX request
        document.getElementById('subscribe-message').innerHTML = 'Ha ocurrido un error. Porfavor intente más tarde.';
      }
    }
  };

  ajax.send('subscriberEmail=' + subscriberEmail);
  return false;
} // Suggestion 2: Add basic email validation before sending the request


function validateEmail(email) {
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
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
/*recursividad del navbar*/

customElements.define('nav-html',
/*#__PURE__*/
function (_HTMLElement) {
  _inherits(_class, _HTMLElement);

  function _class() {
    _classCallCheck(this, _class);

    return _possibleConstructorReturn(this, _getPrototypeOf(_class).call(this));
  }

  _createClass(_class, [{
    key: "connectedCallback",
    value: function connectedCallback() {
      var _this = this;

      fetch(this.getAttribute('src')).then(function (r) {
        return r.text();
      }).then(function (t) {
        var parser = new DOMParser();
        var html = parser.parseFromString(t, 'text/html');
        _this.innerHTML = html.body.innerHTML;
      })["catch"](function (e) {
        return console.error(e);
      });
    }
  }]);

  return _class;
}(_wrapNativeSuper(HTMLElement)));