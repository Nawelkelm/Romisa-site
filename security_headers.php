<?php
// Prevenir que el navegador haga MIME-sniffing
header('X-Content-Type-Options: nosniff');

// Habilitar la protección XSS del navegador
header('X-XSS-Protection: 1; mode=block');

// Prevenir que el sitio sea embebido en iframes (clickjacking)
header('X-Frame-Options: DENY');

// Forzar HTTPS
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

// Content Security Policy
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self'");

// Prevenir exposición de información sensible
header_remove('X-Powered-By');

// Configuración de cookies seguras
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');