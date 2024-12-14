<?php

include("../config/database.php");

// Check if the form has been submitted
$method = $_SERVER['REQUEST_METHOD'];

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Request"]);
    exit;
}
// ...



// Get the selected filter from the URL query string
$selectedFilter = $_GET['selectedFilter'] ?? null;

// If a filter is selected, modify the SQL query

if ($selectedFilter === 'Available') {
    $sql = "SELECT * FROM tbl_book WHERE book_status = 'Available' ORDER BY book_id DESC";
    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the books as JSON
    echo json_encode($books);
    exit;
} else if ($selectedFilter === 'Borrowed') {
    $sql = "SELECT * FROM tbl_book WHERE book_status = 'Borrowed' ORDER BY book_id DESC";
    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the books as JSON
    echo json_encode($books);
    exit;
} else {
    $sql = "SELECT * FROM tbl_book ORDER BY book_id DESC";
    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the books as JSON
    echo json_encode($books);
    exit;
}


