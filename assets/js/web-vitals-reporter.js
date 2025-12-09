/**
 * Web Vitals Reporter para ROMISA
 * Envía métricas de Core Web Vitals a Google Analytics 4
 * 
 * Métricas medidas:
 * - LCP (Largest Contentful Paint): Velocidad de carga
 * - INP (Interaction to Next Paint): Interactividad
 * - CLS (Cumulative Layout Shift): Estabilidad visual
 * - FCP (First Contentful Paint): Primera pintura
 * - TTFB (Time to First Byte): Tiempo de respuesta del servidor
 */

(function() {
  'use strict';

  // Verificar que gtag esté disponible
  function waitForGtag(callback, maxAttempts = 10) {
    let attempts = 0;
    const check = function() {
      if (typeof gtag === 'function') {
        callback();
      } else if (attempts < maxAttempts) {
        attempts++;
        setTimeout(check, 500);
      }
    };
    check();
  }

  // Función para enviar métricas a GA4
  function sendToGA(metric) {
    if (typeof gtag !== 'function') return;

    // Formatear el valor según la métrica
    const value = metric.name === 'CLS' 
      ? Math.round(metric.value * 1000) 
      : Math.round(metric.value);

    // Determinar rating para filtrar en GA
    const rating = metric.rating || 'unknown';

    gtag('event', metric.name, {
      event_category: 'Web Vitals',
      event_label: metric.id,
      value: value,
      non_interaction: true,
      // Datos adicionales para análisis
      metric_rating: rating,
      metric_delta: Math.round(metric.delta),
      page_path: window.location.pathname
    });

    // Log en desarrollo
    if (window.location.hostname === 'localhost') {
      console.log(`[Web Vitals] ${metric.name}: ${metric.value.toFixed(2)} (${rating})`);
    }
  }

  // Cargar web-vitals y registrar métricas
  function initWebVitals() {
    // Importar desde CDN
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/web-vitals@3/dist/web-vitals.iife.js';
    script.onload = function() {
      if (window.webVitals) {
        webVitals.onCLS(sendToGA);
        webVitals.onINP(sendToGA);
        webVitals.onLCP(sendToGA);
        webVitals.onFCP(sendToGA);
        webVitals.onTTFB(sendToGA);
      }
    };
    script.onerror = function() {
      console.warn('[Web Vitals] No se pudo cargar la librería');
    };
    document.head.appendChild(script);
  }

  // Iniciar cuando el DOM esté listo
  if (document.readyState === 'loading') {y
    document.addEventListener('DOMContentLoaded', function() {
      waitForGtag(initWebVitals);
    });
  } else {
    waitForGtag(initWebVitals);
  }
})();
