<?php
declare(strict_types=1);

/**
 * CSRF Token Helper
 * Generate and verify CSRF tokens for session-based protection
 */

class CSRFToken
{
    const TOKEN_NAME = '_csrf_token';
    
    public static function generate(): string
    {
        if (!isset($_SESSION[self::TOKEN_NAME])) {
            $_SESSION[self::TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::TOKEN_NAME];
    }
    
    public static function verify(string $token): bool
    {
        return isset($_SESSION[self::TOKEN_NAME]) && hash_equals($_SESSION[self::TOKEN_NAME], $token);
    }
    
    public static function name(): string
    {
        return self::TOKEN_NAME;
    }
}
