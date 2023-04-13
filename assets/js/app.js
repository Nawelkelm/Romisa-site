function rotacionDeContenido() {
  // Formateamos la Fecha, por ejemplo "2022-06-29"
  var a = new Date();
  var min = a.getMinutes();
  console.log(min);
  if (min >= 1 && min <= 10) {
    texto = 'Conoce nuestra marca destacada Festo!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festovuvs.webp)';
    texto2 = 'Electroválvulas';
    texto3 = 'VUVS-LT25-M52-MD-G14-F8';
    vinculo =
      'https://www.festo.com/ar/es/p/electrovalvula-id_VUVS/?q=vuvs~:festoSortOrderScored';
    vinculo2 =
      'https://www.festo.com/ar/es/a/download-document/datasheet/153053/?fwacid=f8ef87ee73063cd8';
    imagen2 =
      'https://www.festo.com/media/pim/087/D15000100121087_1056x1024.jpg';
    imagen3 =
      'https://www.festo.com/media/pim/886/D15000100133886_1056x1024.jpg';
    texto4 = 'Racor rápido roscado';
    texto5 = 'L-QSL-3/8-12';
    vinculo3 =
      'https://www.festo.com/cat/es-ar_ar/data/doc_ES/PDF/ES/DSBC_ES.PDF';
    imagen4 =
      'https://www.festo.com/cat/xdki/data/PIC/DC18E6D51B704A1CA9739AABE6E29E06.jpg';
    texto6 = 'Cilindro normalizado';
    texto7 = 'DSBC-50-100-PPVA-N3';
  } else if (min > 10 && min <= 20) {
    texto = 'Conoce nuestra marca destacada Fluke!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/fluke.webp)';
    texto2 = 'Comprobación Eléctrica';
    texto3 = '';
    imagen2 =
      'https://fluke.com.ar/_next/image?url=https%3A%2F%2Fstrapi.fluke.com.ar%2Fuploads%2FFLUKE_1775_59f96003a0.jpg&w=640&q=75';
    vinculo = 'https://fluke.com.ar/categoria/comprobacion-electrica';

    imagen3 =
      'https://fluke.com.ar/_next/image?url=https%3A%2F%2Fstrapi.fluke.com.ar%2Fuploads%2FFLUKE_754_d1e12b8afb.jpg&w=640&q=75';
    texto4 = 'Herramientas de Procesos';
    texto5 = '';
    vinculo2 = 'https://fluke.com.ar/categoria/herramientas-de-procesos';
    imagen4 =
      'https://fluke.com.ar/_next/image?url=https%3A%2F%2Fstrapi.fluke.com.ar%2Fuploads%2FFLK_TI_480_629206e815.jpg&w=640&q=75';
    texto6 = 'Medición de Temperatura';
    texto7 = '';
    vinculo3 = 'https://fluke.com.ar/categoria/medicion-de-temperatura';
  } else if (min > 20 && min <= 30) {
    texto = 'Conoce nuestra marca destacada Festo!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festovuvs.webp)';
    texto2 = 'Electroválvulas';
    texto3 = 'VUVS-LT25-M52-MD-G14-F8';
    vinculo =
      'https://www.festo.com/ar/es/p/electrovalvula-id_VUVS/?q=vuvs~:festoSortOrderScored';
    vinculo2 =
      'https://www.festo.com/ar/es/a/download-document/datasheet/153053/?fwacid=f8ef87ee73063cd8';
    imagen2 =
      'https://www.festo.com/media/pim/087/D15000100121087_1056x1024.jpg';
    imagen3 =
      'https://www.festo.com/media/pim/886/D15000100133886_1056x1024.jpg';
    texto4 = 'Racor rápido roscado';
    texto5 = 'L-QSL-3/8-12';
    vinculo3 =
      'https://www.festo.com/cat/es-ar_ar/data/doc_ES/PDF/ES/DSBC_ES.PDF';
    imagen4 =
      'https://www.festo.com/cat/xdki/data/PIC/DC18E6D51B704A1CA9739AABE6E29E06.jpg';
    texto6 = 'Cilindro normalizado';
    texto7 = 'DSBC-50-100-PPVA-N3';
  } else if (min > 30 && min <= 40) {
    texto = 'Conoce nuestra marca destacada Fluke!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/fluke.webp)';
    texto2 = 'Comprobación Eléctrica';
    texto3 = '';
    imagen2 =
      'https://fluke.com.ar/_next/image?url=https%3A%2F%2Fstrapi.fluke.com.ar%2Fuploads%2FFLUKE_1775_59f96003a0.jpg&w=640&q=75';
    vinculo = 'https://fluke.com.ar/categoria/comprobacion-electrica';

    imagen3 =
      'https://fluke.com.ar/_next/image?url=https%3A%2F%2Fstrapi.fluke.com.ar%2Fuploads%2FFLUKE_754_d1e12b8afb.jpg&w=640&q=75';
    texto4 = 'Herramientas de Procesos';
    texto5 = '';
    vinculo2 = 'https://fluke.com.ar/categoria/herramientas-de-procesos';
    imagen4 =
      'https://fluke.com.ar/_next/image?url=https%3A%2F%2Fstrapi.fluke.com.ar%2Fuploads%2FFLK_TI_480_629206e815.jpg&w=640&q=75';
    texto6 = 'Medición de Temperatura';
    texto7 = '';
    vinculo3 = 'https://fluke.com.ar/categoria/medicion-de-temperatura';
  } else {
    texto = 'Conoce nuestra marca destacada Festo!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festovuvs.webp)';
    texto2 = 'Electroválvulas';
    texto3 = 'VUVS-LT25-M52-MD-G14-F8';
    vinculo =
      'https://www.festo.com/ar/es/p/electrovalvula-id_VUVS/?q=vuvs~:festoSortOrderScored';
    vinculo2 =
      'https://www.festo.com/ar/es/a/download-document/datasheet/153053/?fwacid=f8ef87ee73063cd8';
    imagen2 =
      'https://www.festo.com/media/pim/087/D15000100121087_1056x1024.jpg';
    imagen3 =
      'https://www.festo.com/media/pim/886/D15000100133886_1056x1024.jpg';
    texto4 = 'Racor rápido roscado';
    texto5 = 'L-QSL-3/8-12';
    vinculo3 =
      'https://www.festo.com/cat/es-ar_ar/data/doc_ES/PDF/ES/DSBC_ES.PDF';
    imagen4 =
      'https://www.festo.com/cat/xdki/data/PIC/DC18E6D51B704A1CA9739AABE6E29E06.jpg';
    texto6 = 'Cilindro normalizado';
    texto7 = 'DSBC-50-100-PPVA-N3';
  }

  document.images['cambioConteImg'].src = imagen2;
  document.images['cambioConteImg3'].src = imagen3;
  document.images['cambioConteImg4'].src = imagen4;

  // Enviamos el mensaje al elemento con id "mensajecambio"
  //document.images["imagencambio"].src = imagen;
  document.getElementById('mensajecambio').innerHTML = texto;
  document.getElementById('cambiarVinculo').href = vinculo;
  document.getElementById('cambiarVinculo2').href = vinculo2;
  document.getElementById('cambiarVinculo3').href = vinculo3;
  document.getElementById('textoCambio').innerHTML = texto2;
  document.getElementById('AbaTextoCambio').innerHTML = texto3;
  document.getElementById('textoCambio2').innerHTML = texto4;
  document.getElementById('AbaTextoCambio2').innerHTML = texto5;
  document.getElementById('textoCambio3').innerHTML = texto6;
  document.getElementById('AbaTextoCambio3').innerHTML = texto7;
  var images = document.getElementById('imagencambio');
  images.style.backgroundImage = imagen;
}
