<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode('Method Not Allowed');
    return;
}

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate a Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate Bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP POST request JSON body
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['title']) || !isset($data['url'])) {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Error: Missing required parameters title or url in the JSON body.')
    );
    return;
}

$bookmark->setTitle($data['title']);
$bookmark->setUrl($data['url']);

// Create Bookmark
if ($bookmark->create()) {
    echo json_encode(
        array('message' => 'Bookmark created successfully.')
    );
} else {
    echo json_encode(
        array('message' => 'Error: Bookmark could not be created.')
    );
}