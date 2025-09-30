function doSubscribe() {
  var subscriberEmail = document.getElementById('subscriberEmail').value;
  var encodedEmail = encodeURIComponent(subscriberEmail);

  var ajax = new XMLHttpRequest();
  ajax.open('POST', 'newsletter.php', true);
  ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  ajax.onreadystatechange = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        document.getElementById('subscribe-message').innerHTML =
          'Gracias por suscribirse.';
      } else {
        document.getElementById('subscribe-message').innerHTML =
          'Error en el servidor.';
      }
    }
  };

  ajax.onerror = function () {
    document.getElementById('subscribe-message').innerHTML =
      'Error en la solicitud AJAX.';
  };

  ajax.send('subscriberEmail=' + encodedEmail);
  return false;
}
