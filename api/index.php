<?php
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT; // Import JWT library

$app = AppFactory::create();

// Use this function to generate a token after user login
function generateToken($userId) {
    $secretKey = 'your_secret_key'; // Replace with a secure secret key
    $tokenPayload = array(
        "userId" => $userId,
        "iat" => time(),
        "exp" => time() + 3600 // Token expiration time (1 hour)
    );
    $token = JWT::encode($tokenPayload, $secretKey);
    return $token;
}

// Example login endpoint
$app->post('/login', function (Request $request, Response $response) {
    // Implement user authentication logic

    // After successful authentication, generate and return a token
    $userId = 1; // Replace with the actual user ID
    $token = generateToken($userId);
    return $response->withJson(['token' => $token]);
});

// Example authenticated endpoint
$app->get('/authenticated', function (Request $request, Response $response) {
    // Extract and verify the token
    $token = $request->getHeaderLine('Authorization');
    $secretKey = 'your_secret_key'; // Replace with the same secret key used for encoding

    try {
        $decoded = JWT::decode($token, $secretKey, array('HS256'));
        // Token is valid, perform authorized actions
        return $response->withJson(['message' => 'Authenticated']);
    } catch (Exception $e) {
        return $response->withStatus(401)->withJson(['error' => 'Unauthorized']);
    }
});

$app->run();
