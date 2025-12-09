$(document).ready(function () {
  function showPopup() {
    // Ocultar temporalmente el botón y mensaje de WhatsApp cuando aparece el popup
    const whatsappBtn = document.getElementById('hiddenWhatsapp');
    const chatBubble = document.querySelector('.whatsapp-chat-bubble');
    
    if (whatsappBtn) whatsappBtn.classList.add('whatsapp-hidden');
    if (chatBubble) chatBubble.classList.remove('show');
    
    $('.pop-up').addClass('show');
    $('.pop-up-wrap').addClass('show');
  }

  $('#close').click(function () {
    $('.pop-up').removeClass('show');
    $('.pop-up-wrap').removeClass('show');
    
    // Mostrar el mensaje de WhatsApp después de cerrar el popup
    setTimeout(() => {
      const whatsappBtn = document.getElementById('hiddenWhatsapp');
      if (whatsappBtn) {
        // Quitar la clase que oculta el botón
        whatsappBtn.classList.remove('whatsapp-hidden');
        
        const chatBubble = document.querySelector('.whatsapp-chat-bubble');
        const notification = document.querySelector('.whatsapp-notification');
        
        if (chatBubble) {
          chatBubble.classList.add('show');
          chatBubble.classList.add('highlight-animation');
        }
        
        if (notification) {
          notification.classList.add('show');
        }
        
        whatsappBtn.classList.add('attention-animation');
      }
    }, 1500); // Pequeño retraso después de cerrar el popup
  });
  
  // También agregar evento al botón "close-button" que también cierra el popup
  $('#close-button').click(function() {
    $('.pop-up').removeClass('show');
    $('.pop-up-wrap').removeClass('show');
    
    // Mostrar el mensaje de WhatsApp después de cerrar el popup usando el botón "Seguir en la página"
    setTimeout(() => {
      const whatsappBtn = document.getElementById('hiddenWhatsapp');
      if (whatsappBtn) {
        // Quitar la clase que oculta el botón
        whatsappBtn.classList.remove('whatsapp-hidden');
        
        const chatBubble = document.querySelector('.whatsapp-chat-bubble');
        const notification = document.querySelector('.whatsapp-notification');
        
        if (chatBubble) {
          chatBubble.classList.add('show');
          chatBubble.classList.add('highlight-animation');
        }
        
        if (notification) {
          notification.classList.add('show');
        }
        
        whatsappBtn.classList.add('attention-animation');
      }
    }, 1500); // Pequeño retraso después de cerrar el popup
  });
  
  setTimeout(showPopup, 8000);
});
//10500
