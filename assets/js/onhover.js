$(function () { // Shorthand for $(document).ready()

  const $window = $(window); // Cache the window object
  let anchoVentana = $window.width(); // Get initial width

  const boxesData = [
    { box: '.box', boxi: '.boxi' },
    { box: '.box2', boxi: '.box3' },
    { box: '.box4', boxi: '.box5' },
    { box: '.box6', boxi: '.box7' },
    { box: '.box8', boxi: '.box9' },
    { box: '.box10', boxi: '.box11' },
    { box: '.box12', boxi: '.box13' },
    { box: '.box14', boxi: '.box15' },
    { box: '.box16', boxi: '.box17' },
    { box: '.box18', boxi: '.box19' },
    { box: '.box20', boxi: '.box21' },
  ];

  const handleHover = (boxSelector, boxiSelector) => {
    const $box = $(boxSelector);
    const $boxi = $(boxiSelector);

    $box.on('mouseover', () => {
      $box.hide();
      $boxi.show();
    });

    $boxi.on('mouseleave', () => {
      $boxi.hide();
      $box.show();
    });
  };

  const handleHoverMobile = (boxSelector, boxiSelector) => {
    const $box = $(boxSelector);
    const $boxi = $(boxiSelector);

    $box.on('mouseover', () => {
      $boxi.hide();
      $box.css('display', 'contents');
    });

    $boxi.on('mouseleave', () => {
      $box.css('display', 'contents');
      $boxi.hide();
    });
  };

  const funcion1 = () => {
    boxesData.forEach(({ box, boxi }) => {
      handleHover(box, boxi);
    });
  };

  const funcion2 = () => {
    alert('Que tenga un Excelente día!');
  };

  const funcion3 = () => {
    boxesData.forEach(({ box, boxi }) => {
      handleHoverMobile(box, boxi);
    });
  };

  const updateBehavior = () => {
    anchoVentana = $window.width();
    console.log(anchoVentana);

    // Remove existing event handlers before applying new ones
    boxesData.forEach(({ box, boxi }) => {
      $(box).off('mouseover');
      $(boxi).off('mouseleave');
    });

    if (anchoVentana > 1000) {
      funcion1();
    } else if (anchoVentana > 750 && anchoVentana < 1000) {
      funcion2();
    } else {
      funcion3();
    }
  };

  $window.on('resize', updateBehavior); // Use jQuery's resize event

  updateBehavior(); // Call initially to set the correct behavior
});
