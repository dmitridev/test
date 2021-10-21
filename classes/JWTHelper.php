<?php
include __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;

class JWTHelper
{
    public static function generateToken(string $key, int $exp = 3_600_000): string
    {
        $payload = array(
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => time(),
            "exp" => time() + $exp,
        );

        $token = JWT::encode($payload, $key);
        return $token;
    }

    public static function validateToken(string $token, string $secret): bool
    {
        try {
            JWT::decode($token, $secret, ['HS256']);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
