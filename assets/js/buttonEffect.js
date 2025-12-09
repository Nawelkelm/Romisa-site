    /**
 * Script para mejorar la experiencia del botón flotante
 * Hace que el botón aparezca después de un tiempo o scroll
 */
document.addEventListener('DOMContentLoaded', function() {
    const floatingBtn = document.getElementById('ocultar');
    
    if (!floatingBtn) return;

    // Ocultar el botón inicialmente
    floatingBtn.style.opacity = '0';
    floatingBtn.style.display = 'none';

    // Función para mostrar el botón con animación
    function showButton() {
        if (floatingBtn.style.display === 'none') {
            floatingBtn.style.display = 'flex';
            // Agregamos la clase para la animación
            setTimeout(() => {
                floatingBtn.classList.add('show-button');
            }, 100);
            
            // Solo activamos el evento de scroll una vez
            window.removeEventListener('scroll', scrollHandler);
        }
    }

    // Mostrar después de 3 segundos
    setTimeout(showButton, 3000);

    // También mostrar después de scroll
    const scrollHandler = function() {
        if (window.scrollY > 300) {
            showButton();
        }
    };
    
    window.addEventListener('scroll', scrollHandler);
    
    // Agregar efecto de rebote al hacer hover
    floatingBtn.addEventListener('mouseover', function() {
        this.style.transform = 'translateY(-7px) scale(1.05)';
    });
    
    floatingBtn.addEventListener('mouseout', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
    
    // Asegurar que el enlace se abra en una nueva pestaña
    if (floatingBtn.getAttribute('href').includes('ecommerce.romisa.com.ar')) {
        floatingBtn.setAttribute('target', '_blank');
        floatingBtn.setAttribute('rel', 'noopener noreferrer');
    }
});