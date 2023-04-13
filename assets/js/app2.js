function rotacionDeContenido() {
  // Formateamos la Fecha, por ejemplo "2022-06-29"
  var fechaHoy = new Date().toISOString().slice(0, 10);

  // Colocamos la fecha formateada dentro de otra variable
  var soloFechaHoy = fechaHoy;

  //let fechaRota = ['2022-11-01', '2022-11-02', '2022-11-03', '2022-11-04', '2022-11-05', '2022-11-06', '2022-11-07', '2022-11-08', '2022-11-09', '2022-11-10', '2022-11-11', '2022-11-12', '2022-11-13', '2022-11-14', '2022-11-15', '2022-11-16', '2022-11-17', '2022-11-18', '2022-11-19', '2022-11-20', '2022-11-21', '2022-11-22', '2022-11-23', '2022-11-24', '2022-11-25', '2022-11-26', '2022-11-27', '2022-11-28', '2022-11-29', '2022-11-30', '2022-11-31']
  // Definimos las fechas en donde se debe mostrar el Mensaje
  //primera semana
  var primeraFecha = '2023-02-01';
  var segundaFecha = '2023-02-02';
  var terceraFecha = '2023-02-03';
  var cuartaFecha = '2023-02-04';
  var quintaFecha = '2023-02-05';
  var sextaFecha = '2023-02-06';
  var septimaFecha = '2023-02-07';
  //segunda semana
  var octavaFecha = '2023-02-08';
  var novenaFecha = '2023-02-09';
  var decimaFecha = '2023-02-10';
  var decimaPriFecha = '2023-02-11';
  var decimaSegFecha = '2023-02-12';
  var decimaTerFecha = '2023-02-13';
  var decimaCuaFecha = '2023-02-14';
  //tercer semana
  var decimaQuiFecha = '2023-02-15';
  var decimaSexFecha = '2023-02-16';
  var decimaSepFecha = '2023-02-17';
  var decimaOctFecha = '2023-02-18';
  var decimaNovFecha = '2023-02-19';
  var duoDecimaFecha = '2023-02-20';
  var duoDecimaPriFecha = '2023-02-21';
  var duoDecimaSegFecha = '2023-02-22';
  //cuarta semana
  var duoDecimaTerFecha = '2023-02-23';
  var duoDecimaCuaFecha = '2023-02-24';
  var duoDecimaQuiFecha = '2023-02-25';
  var duoDecimaSexFecha = '2023-02-26';
  var duoDecimaSepFecha = '2023-02-27';
  var duoDecimaOctFecha = '2023-02-28';
  var duoDecimaNovFecha = '2023-02-29';
  var trioDecimaFecha = '2023-02-30';

  // Verificamos si la fecha es igual a la primera semana del mes y mostramos un mensaje con imagen nueva
  if (
    soloFechaHoy == primeraFecha ||
    soloFechaHoy == segundaFecha ||
    soloFechaHoy == terceraFecha ||
    soloFechaHoy == cuartaFecha ||
    soloFechaHoy == quintaFecha ||
    soloFechaHoy == sextaFecha ||
    soloFechaHoy == septimaFecha
  ) {
    texto = 'Conoce nuestra marca destacada Festo!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festovuvs.webp)';
    texto2 = 'Electroválvulas';
    texto3 = 'VUVS-LT25-M52-MD-G14-F8';
    imagen2 =
      'https://www.festo.com/media/pim/087/D15000100121087_1056x1024.jpg';
    vinculo =
      'https://www.festo.com/ar/es/p/electrovalvula-id_VUVS/?q=vuvs~:festoSortOrderScored';
    // Verificamos si la fecha es igual a la segunda semana del mes y mostramos un mensaje con imagen nueva
  } else if (
    soloFechaHoy == octavaFecha ||
    soloFechaHoy == novenaFecha ||
    soloFechaHoy == decimaFecha ||
    soloFechaHoy == decimaPriFecha ||
    soloFechaHoy == decimaSegFecha ||
    soloFechaHoy == decimaTerFecha ||
    soloFechaHoy == decimaCuaFecha
  ) {
    texto = 'Conoce nuestra marca destacada Festo!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festo.webp)';
    texto2 = 'Racor rápido roscado';
    texto3 = 'L-QSL-3/8-12';
    imagen2 =
      'https://www.festo.com/media/pim/886/D15000100133886_1056x1024.jpg';
    vinculo =
      'https://www.festo.com/ar/es/a/download-document/datasheet/153053/?fwacid=f8ef87ee73063cd8';

    // Verificamos si la fecha es igual a la tercera semana del mes y mostramos un mensaje con imagen nueva
  } else if (
    soloFechaHoy == decimaQuiFecha ||
    soloFechaHoy == decimaSexFecha ||
    soloFechaHoy == decimaSepFecha ||
    soloFechaHoy == decimaOctFecha ||
    soloFechaHoy == decimaNovFecha ||
    soloFechaHoy == duoDecimaFecha ||
    soloFechaHoy == duoDecimaPriFecha ||
    soloFechaHoy == duoDecimaSegFecha
  ) {
    texto = 'Conoce nuestra marca destacada Festo!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festodsbc.webp)';
    texto2 = 'Cilindro normalizado';
    texto3 = 'DSBC-50-100-PPVA-N3';
    imagen2 =
      'https://www.festo.com/cat/xdki/data/PIC/DC18E6D51B704A1CA9739AABE6E29E06.jpg';
    vinculo =
      'https://www.festo.com/cat/es-ar_ar/data/doc_ES/PDF/ES/DSBC_ES.PDF';

    // Verificamos si la fecha es igual a la cuarta semana del mes y mostramos un mensaje con imagen nueva
  } else if (
    soloFechaHoy == duoDecimaTerFecha ||
    soloFechaHoy == duoDecimaCuaFecha ||
    soloFechaHoy == duoDecimaQuiFecha ||
    soloFechaHoy == duoDecimaSexFecha ||
    soloFechaHoy == duoDecimaSepFecha ||
    soloFechaHoy == duoDecimaOctFecha ||
    soloFechaHoy == duoDecimaNovFecha ||
    soloFechaHoy == trioDecimaFecha
  ) {
    texto = ' Conoce nuestra marca destacada Festo!';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/festoPUN.webp)';
    texto2 = 'Tubo de plástico';
    texto3 = 'PUN-H-10X1,5-NT';
    imagen2 =
      'https://www.festo.com/cat/xdki/data/PIC/B648AE374EC74DE28DD112B3FF3F31AD.jpg';
    vinculo =
      'https://www.festo.com/cat/es-ar_ar/data/doc_ES/PDF/ES/OD-TUBING_ES.PDF';
  }

  // Si ninguna fecha coincide, entonces mostramos un mensaje por defecto
  else {
    texto = 'visita nuestra pagina';
    texto2 = 'Conoce nuestro';
    texto3 = ' portafolio de productos';
    imagen =
      'url(https://romisa.com.ar/sandbox/romisite/assets/img/romisite.webp)';
    imagen2 =
      'https://romisa.com.ar/sandbox/romisite/assets/img/android-chrome-256x256.png';
    vinculo = '#';
  }

  document.images['cambioConteImg'].src = imagen2;
  // Enviamos el mensaje al elemento con id "mensajecambio"
  //document.images["imagencambio"].src = imagen;
  document.getElementById('mensajecambio').innerHTML = texto;
  document.getElementById('cambiarVinculo').href = vinculo;
  document.getElementById('textoCambio').innerHTML = texto2;
  document.getElementById('AbaTextoCambio').innerHTML = texto3;
  var images = document.getElementById('imagencambio');
  images.style.backgroundImage = imagen;
}
