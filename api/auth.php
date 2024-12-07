<?php
// Connect to the database
include("../config/database.php");

// Check if the form has been submitted
$method = $_SERVER['REQUEST_METHOD'];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Request"]);
    exit;
}
// get the input
$input = json_decode(file_get_contents("php://input"), true);

$username = isset($input['username']) ? $input['username'] : null;
$password = isset($input['user_password']) ? $input['user_password'] : null;


// Check if the username and password are not empty
if ($username == "" || $password == "") {
    // Display an error message
    echo "yeah";
    exit;
}

// Query the database to check if the user exists
$sql = "SELECT * FROM tbl_user WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":username", $username);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the user exists
if (count($results) > 0) {
    // Get the user data
    $row = $results[0];

    // Check if the password is correct
    if ($password == $row["user_password"]) {
        // Start a session
        session_start();

        // Set the user data in the session
        $_SESSION["username"] = $row["username"];
        $_SESSION["user_role"] = $row["user_role"];

        // Return the user data as a JSON response
        echo json_encode([
            "username" => $row["username"],
            "user_role" => $row["user_role"],
        ]);
        exit;
    } else {
        // Display an error message
        echo json_encode([
            "error" => "Incorrect password"
        ]);
        exit;
    }
} else {
    // Display an error message
    echo json_encode([
        "error" => "User does not exist"
    ]);
    exit;
}


// Close the database connection
$pdo = null;
