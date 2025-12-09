/**
 * Script mejorado para WhatsApp - ROMISA
 * Incluye elementos interactivos, animaciones y mensajes estratégicos
 */
document.addEventListener('DOMContentLoaded', function() {
    const whatsappBtn = document.getElementById('hiddenWhatsapp');
    
    if (!whatsappBtn) {
        console.error('Elemento de WhatsApp no encontrado');
        return;
    }
    
    // Hacer visible el botón al inicio
    whatsappBtn.style.display = 'block';
    
    // Agregar el contador de notificaciones
    const notification = document.createElement('div');
    notification.className = 'whatsapp-notification';
    notification.textContent = '1';
    whatsappBtn.appendChild(notification);
    
    // Crear el mensaje de chat que aparece al inicio
    const chatBubble = document.createElement('div');
    chatBubble.className = 'whatsapp-chat-bubble';
    chatBubble.innerHTML = `
        <div class="chat-header">
            <img src="./assets/img/logo.svg" alt="Romisa" width="35">
            <div>
                <strong>Romisa - Soporte técnico</strong>
                <div class="online-status"><span class="online-dot"></span> En línea</div>
            </div>
        </div>
        <div class="chat-message">
            <strong>¡Hola! 👋 ¿En qué podemos ayudarte?</strong>
            <p>Nuestros especialistas están listos para asesorarte sobre el producto ideal para tu necesidad. ¡Respuesta inmediata!</p>
        </div>
        <a href="${whatsappBtn.href}" class="btn-whatsapp" target="_blank">
            <i class="fa fa-whatsapp"></i> CONSULTAR AHORA
        </a>
        <div class="chat-footer">
            <small>✅ Atención personalizada y respuesta garantizada</small>
        </div>
    `;
    document.body.appendChild(chatBubble);
    
    // El banner lateral ha sido eliminado para mostrar solo un mensaje
    
    // Función para mostrar el chat bubble después de cerrar el popup
    function showChatBubble() {
        // No mostrar automáticamente, esperar a que se cierre el popup
    }
    
    // Crear botón de cierre para el chat bubble
    const closeButton = document.createElement('div');
    closeButton.className = 'chat-close-btn';
    closeButton.innerHTML = '×';
    chatBubble.appendChild(closeButton);
    
    // Función para cerrar el chat bubble al hacer clic en el botón
    closeButton.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        chatBubble.classList.remove('show');
        
        // Programar que vuelva a aparecer después de un tiempo
        setTimeout(() => {
            chatBubble.classList.add('show');
            notification.classList.add('show');
        }, 45000); // Reaparece después de 45 segundos
    });
    
    // No mostramos el mensaje de WhatsApp automáticamente
    // Se mostrará cuando el usuario cierre el popup
    try {
        showChatBubble();
    } catch (error) {
        console.error('Error al configurar elementos de WhatsApp:', error);
    }
    
    // Mensajes persuasivos que cambian con el tiempo
    const persuasiveMessages = [
        '¿Necesita asesoramiento técnico para elegir el producto correcto?',
        '¡Stock disponible! Consulte ahora y reciba su producto rápidamente',
        'Nuestros especialistas pueden ayudarle a encontrar la mejor solución',
        '¿Dudas sobre especificaciones técnicas? Pregúntenos ahora',
        'Consulte descuentos por cantidad en sus pedidos'
    ];
    
    let messageIndex = 0;
    
    // Programar mostrar el chat bubble ocasionalmente con mensajes cambiantes
    setInterval(() => {
        if (Math.random() > 0.3) { // 70% de probabilidad
            // Mostrar el chat bubble con mensaje aleatorio
            const messageElement = chatBubble.querySelector('.chat-message p');
            if (messageElement) {
                messageElement.textContent = persuasiveMessages[messageIndex];
                messageIndex = (messageIndex + 1) % persuasiveMessages.length;
            }
            
            chatBubble.classList.add('show');
            notification.classList.add('show');
            
            setTimeout(() => {
                chatBubble.classList.remove('show');
            }, 8000);
        }
    }, 45000); // Cada 45 segundos evalúa si mostrar
    
    // Hacer que el botón rebote periódicamente
    setInterval(() => {
        whatsappBtn.classList.add('bounce-animation');
        notification.classList.add('show');
        
        setTimeout(() => {
            whatsappBtn.classList.remove('bounce-animation');
        }, 2000);
    }, 30000);
    
    // Interacciones con el botón - Mejoradas
    whatsappBtn.addEventListener('mouseover', function() {
        this.style.transform = 'scale(1.1)';
        chatBubble.classList.add('show');
        
        // Destacar el mensaje al pasar sobre el botón
        chatBubble.classList.add('highlight-animation');
    });
    
    whatsappBtn.addEventListener('mouseout', function() {
        this.style.transform = 'scale(1)';
        
        // Mantener visible el mensaje, ya no lo ocultamos al salir
        // para maximizar conversiones
        chatBubble.classList.remove('highlight-animation');
    });
    
    // Al hacer clic en el botón
    whatsappBtn.addEventListener('click', function() {
        notification.classList.remove('show');
        // Registrar evento de clic para análisis (se podría implementar)
        // sendAnalyticsEvent('whatsapp_button_click');
    });
    
    // Hacer que el chat bubble sea interactivo
    chatBubble.addEventListener('click', function(e) {
        // Solo si no se hizo clic en el botón de cierre o en el botón de WhatsApp
        if (!e.target.closest('.chat-close-btn') && !e.target.closest('.btn-whatsapp')) {
            // Abrir WhatsApp al hacer clic en cualquier parte del mensaje
            window.open(whatsappBtn.href, '_blank');
        }
    });
    
    // Añadir efecto hover al pasar sobre el chat bubble
    chatBubble.addEventListener('mouseenter', function() {
        whatsappBtn.classList.add('highlight-animation');
    });
    
    chatBubble.addEventListener('mouseleave', function() {
        whatsappBtn.classList.remove('highlight-animation');
        // Ya no ocultamos el mensaje al salir
    });
});