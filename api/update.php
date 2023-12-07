<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
    header('Allow: PUT');
    http_response_code(405);
    echo json_encode('Method Not Allowed');
    return;
}

// Response Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate a Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate Bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP PUT request JSON body
$data = json_decode(file_get_contents("php://input"));
if (!$data || !$data->id || !$data->title || !$data->url) {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Error: Missing required parameters id, title, and url in the JSON body.')
    );
    return;
}

$bookmark->setId($data->id);
$bookmark->setTitle($data->title);
$bookmark->setUrl($data->url);

// Update the bookmark
if ($bookmark->update()) {
    echo json_encode(
        array('message' => 'Bookmark updated successfully.')
    );
} else {
    echo json_encode(
        array('message' => 'Error: Bookmark could not be updated.')
    );
}