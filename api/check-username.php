<?php
// Connect to the database
include("../config/database.php");

// Check if the form has been submitted
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== "POST") {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Request"]);
    exit;
}

// get the input
$input = json_decode(file_get_contents("php://input"), true);

$username = isset($input['username']) ? $input['username'] : null;

// Check if the username exists in the database
$query = "SELECT * FROM tbl_user WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($results) > 0) {
    echo json_encode([
        'exists' => true
    ]);
    exit;
} else {
    echo json_encode([
        'exists' => false
    ]);
    exit;
}



$pdo = null;
