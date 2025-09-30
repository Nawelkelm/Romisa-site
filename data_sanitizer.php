<?php
class DataSanitizer {
    // Sanitizar strings
    public static function sanitizeString($string) {
        return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
    }

    // Sanitizar email
    public static function sanitizeEmail($email) {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }

    // Sanitizar número entero
    public static function sanitizeInt($int) {
        return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
    }

    // Sanitizar float
    public static function sanitizeFloat($float) {
        return filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    // Sanitizar URL
    public static function sanitizeURL($url) {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    // Sanitizar array
    public static function sanitizeArray($array, $type = 'string') {
        if (!is_array($array)) return [];
        
        return array_map(function($item) use ($type) {
            switch($type) {
                case 'int':
                    return self::sanitizeInt($item);
                case 'float':
                    return self::sanitizeFloat($item);
                case 'email':
                    return self::sanitizeEmail($item);
                case 'url':
                    return self::sanitizeURL($item);
                default:
                    return self::sanitizeString($item);
            }
        }, $array);
    }

    // Sanitizar nombre de archivo
    public static function sanitizeFilename($filename) {
        // Remover caracteres especiales y espacios
        $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $filename);
        // Prevenir nombres de archivo que empiecen con punto
        $filename = ltrim($filename, '.');
        return $filename;
    }

    // Sanitizar datos JSON
    public static function sanitizeJSON($json) {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        return json_encode(self::sanitizeArray($data));
    }

    // Validar y sanitizar fecha
    public static function sanitizeDate($date) {
        $clean = date('Y-m-d', strtotime($date));
        return $clean !== '1970-01-01' ? $clean : false;
    }

    // Sanitizar entrada de SQL
    public static function escapeSQLInput($string) {
        return addslashes($string);
    }
}