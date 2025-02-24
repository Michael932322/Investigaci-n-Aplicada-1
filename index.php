<?php
require_once __DIR__ . '/vendor/autoload.php'; // Cargar el autoload de Composer
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load(); // Cargar las variables de entorno desde .env

// Obtener la clave secreta desde las variables de entorno
$secretKey = $_ENV['SECRET_KEY'];

// Función para verificar y decodificar el token
function verificarToken($token) {
    global $secretKey;

    try {
        // Decodificar el token
        $decoded = JWT::decode($token, new \Firebase\JWT\Key($secretKey, 'HS256')); // Usar la clase Key para especificar la clave y el algoritmo
        return (array) $decoded; // Devolver los datos decodificados como un array
    } catch (Exception $e) {
        // Si hay un error (token inválido, expirado, etc.), retornar null
        return null;
    }
}

// Función para autenticar la solicitud
function authenticate($data) {
    if (isset($data['Authorization'])) {
        // Obtener el token de la cabecera Authorization
        $authHeader = $data['Authorization'];

        // Verificar que el formato del token sea correcto: "Bearer <token>"
        $arr = explode(" ", $authHeader);
        if (count($arr) == 2) {
            $token = $arr[1]; // El token es la segunda parte del encabezado

            // Decodificar y verificar el token
            $decoded = verificarToken($token);
            if ($decoded) {
                return $decoded; // Si el token es válido, devolver los datos decodificados
            }
        }
    }
    return null; // Si no hay token o es inválido, devolver null
}

// Supongamos que los datos vienen de una solicitud HTTP
$data = [
    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDAzNjIwMzksImV4cCI6MTc0MDM2NTYzOSwic3ViIjoxMjN9.Aq7gtNEy8PSUNi7HMpi_qDgM3x2bui7t7DahcLUJUhU' // Sustituir <tu_token_aqui> por un token válido
];

// Llamar a la función de autenticación
$userData = authenticate($data);

if ($userData) {
    echo "Usuario autenticado. Datos: ";
    print_r($userData); // Mostrar los datos del usuario decodificados
} else {
    echo "Autenticación fallida.";
}
?>