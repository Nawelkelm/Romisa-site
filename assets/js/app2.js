function rotacionDeContenido() {
  var fechas = [
    '2023-02-01',
    '2023-02-02',
    '2023-02-03',
    '2023-02-04',
    '2023-02-05',
    '2023-02-06',
    '2023-02-07',
    '2023-02-08',
    '2023-02-09',
    '2023-02-10',
    '2023-02-11',
    '2023-02-12',
    '2023-02-13',
    '2023-02-14',
    '2023-02-15',
    '2023-02-16',
    '2023-02-17',
    '2023-02-18',
    '2023-02-19',
    '2023-02-20',
    '2023-02-21',
    '2023-02-22',
    '2023-02-23',
    '2023-02-24',
    '2023-02-25',
    '2023-02-26',
    '2023-02-27',
    '2023-02-28',
    '2023-02-29',
    '2023-02-30',
  ];

  var fechaHoy = new Date().toISOString().slice(0, 10);
  var soloFechaHoy = fechaHoy;

  if (fechas.includes(soloFechaHoy)) {
    var texto = 'Conoce nuestra marca destacada Festo!';
    var imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festovuvs.webp)';
    var texto2 = 'Electroválvulas';
    var texto3 = 'VUVS-LT25-M52-MD-G14-F8';
    var imagen2 =
      'https://www.festo.com/media/pim/087/D15000100121087_1056x1024.jpg';
    var vinculo =
      'https://www.festo.com/ar/es/p/electrovalvula-id_VUVS/?q=vuvs~:festoSortOrderScored';
  } else if (fechas.includes(soloFechaHoy)) {
    var texto = 'Conoce nuestra marca destacada Festo!';
    var imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festo.webp)';
    var texto2 = 'Racor rápido roscado';
    var texto3 = 'L-QSL-3/8-12';
    var imagen2 =
      'https://www.festo.com/media/pim/886/D15000100133886_1056x1024.jpg';
    var vinculo =
      'https://www.festo.com/ar/es/a/download-document/datasheet/153053/?fwacid=f8ef87ee73063cd8';
  } else if (fechas.includes(soloFechaHoy)) {
    var texto = 'Conoce nuestra marca destacada Festo!';
    var imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festodsbc.webp)';
    var texto2 = 'Cilindro normalizado';
    var texto3 = 'DSBC-50-100-PPVA-N3';
    var imagen2 =
      'https://www.festo.com/cat/xdki/data/PIC/DC18E6D51B704A1CA9739AABE6E29E06.jpg';
    var vinculo =
      'https://www.festo.com/cat/es-ar_ar/data/doc_ES/PDF/ES/DSBC_ES.PDF';
  } else if (fechas.includes(soloFechaHoy)) {
    var texto = ' Conoce nuestra marca destacada Festo!';
    var imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festoPUN.webp)';
    var texto2 = 'Tubo de plástico';
    var texto3 = 'PUN-H-10X1,5-NT';
    var imagen2 =
      'https://www.festo.com/cat/xdki/data/PIC/B648AE374EC74DE28DD112B3FF3F31AD.jpg';
    var vinculo =
      'https://www.festo.com/cat/es-ar_ar/data/doc_ES/PDF/ES/OD-TUBING_ES.PDF';
  } else {
    var texto = 'visita nuestra pagina';
    var texto2 = 'Conoce nuestro';
    var texto3 = ' portafolio de productos';
    var imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/romisite.webp)';
    var imagen2 =
      'https://romisa.com.ar/sandbox/romisite/assets/img/android-chrome-256x256.png';
    var vinculo = '#';
  }

  return {
    texto: texto,
    imagen2: imagen2,
    vinculo: vinculo,
    texto2: texto2,
    texto3: texto3,
    imagen: imagen,
  };
}

// Handle DOM manipulation outside the function
var content = rotacionDeContenido();
document.images['cambioConteImg'].src = content.imagen2;
document.getElementById('mensajecambio').innerHTML = content.texto;
document.getElementById('cambiarVinculo').href = content.vinculo;
document.getElementById('textoCambio').innerHTML = content.texto2;
document.getElementById('AbaTextoCambio').innerHTML = content.texto3;
var images = document.getElementById('imagencambio');
images.style.backgroundImage = content.imagen;
