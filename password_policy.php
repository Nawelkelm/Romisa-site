<?php
class PasswordPolicy {
    private static $minLength = 8;
    private static $requireUppercase = true;
    private static $requireLowercase = true;
    private static $requireNumbers = true;
    private static $requireSpecial = true;
    private static $maxAttempts = 5;
    private static $lockoutTime = 900; // 15 minutos en segundos

    // Validar política de contraseña
    public static function validatePassword($password) {
        $errors = [];

        if (strlen($password) < self::$minLength) {
            $errors[] = "La contraseña debe tener al menos " . self::$minLength . " caracteres";
        }

        if (self::$requireUppercase && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "La contraseña debe contener al menos una letra mayúscula";
        }

        if (self::$requireLowercase && !preg_match('/[a-z]/', $password)) {
            $errors[] = "La contraseña debe contener al menos una letra minúscula";
        }

        if (self::$requireNumbers && !preg_match('/[0-9]/', $password)) {
            $errors[] = "La contraseña debe contener al menos un número";
        }

        if (self::$requireSpecial && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "La contraseña debe contener al menos un carácter especial";
        }

        return empty($errors) ? true : $errors;
    }

    // Hashear contraseña de forma segura
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }

    // Verificar contraseña
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    // Verificar si la contraseña necesita ser rehasheada
    public static function needsRehash($hash) {
        return password_needs_rehash($hash, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }

    // Generar contraseña segura
    public static function generateSecurePassword($length = 16) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        $password = '';
        
        // Asegurar al menos un carácter de cada tipo
        $password .= chr(random_int(65, 90)); // Mayúscula
        $password .= chr(random_int(97, 122)); // Minúscula
        $password .= chr(random_int(48, 57)); // Número
        $password .= '!@#$%^&*()_+-=[]{}|;:,.<>?'[random_int(0, 30)]; // Especial
        
        // Rellenar el resto
        while(strlen($password) < $length) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        // Mezclar los caracteres
        return str_shuffle($password);
    }
}

// Clase para manejar intentos de login
class LoginAttempts {
    private static function getAttemptsKey($username) {
        return 'login_attempts_' . hash('sha256', $username);
    }

    public static function recordAttempt($username) {
        $key = self::getAttemptsKey($username);
        $attempts = $_SESSION[$key] ?? ['count' => 0, 'time' => time()];
        $attempts['count']++;
        $attempts['time'] = time();
        $_SESSION[$key] = $attempts;
    }

    public static function isBlocked($username) {
        $key = self::getAttemptsKey($username);
        $attempts = $_SESSION[$key] ?? ['count' => 0, 'time' => 0];
        
        // Resetear si ha pasado el tiempo de bloqueo
        if (time() - $attempts['time'] > PasswordPolicy::$lockoutTime) {
            unset($_SESSION[$key]);
            return false;
        }
        
        return $attempts['count'] >= PasswordPolicy::$maxAttempts;
    }

    public static function resetAttempts($username) {
        $key = self::getAttemptsKey($username);
        unset($_SESSION[$key]);
    }
}