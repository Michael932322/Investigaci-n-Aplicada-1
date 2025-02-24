<?php
require_once __DIR__ . '/vendor/autoload.php'; // Cargar el autoload de Composer
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

// Cargar variables de entorno desde .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$secretKey = $_ENV['SECRET_KEY'];

// Generación de un token
$issuedAt = time();
$expirationTime = $issuedAt + 3600;  // El token expirará en 1 hora
$payload = array(
    "iat" => $issuedAt,
    "exp" => $expirationTime,
    "sub" => 123  // ID del usuario (por ejemplo)
);

// Generar el token con el tercer parámetro 'HS256'
$jwt = JWT::encode($payload, $secretKey, 'HS256');
echo "Token generado: " . $jwt;
?>