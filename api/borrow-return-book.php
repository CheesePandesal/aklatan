<?php
date_default_timezone_set('Asia/Manila');
// Database connection
include("../config/database.php");

// Determine request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        getBooks($pdo);
        break;
    case "PUT":
        updateBook($pdo);
        break;
    default:
        echo json_encode(["error" => "Invalid request method"]);
}

function getBooks($pdo)
{
    $id = isset($_GET['bookId']) ? $_GET['bookId'] : null;

    if ($id) {
        $sql = "SELECT book_id, book_title, book_author, book_publisher, book_publication_year, book_status, 
                DATE_FORMAT(book_borrowed_date, '%Y-%m-%d %H:%i:%s') as book_borrowed_date,
                DATE_FORMAT(book_returned_date, '%Y-%m-%d %H:%i:%s') as book_returned_date
                FROM tbl_book WHERE book_id = :book_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":book_id", $id);
    } else {
        $sql = "SELECT book_id, book_title, book_author, book_publisher, book_publication_year, book_status, 
                DATE_FORMAT(book_borrowed_date, '%Y-%m-%d %H:%i:%s') as book_borrowed_date,
                DATE_FORMAT(book_returned_date, '%Y-%m-%d %H:%i:%s') as book_returned_date
                FROM tbl_book ORDER BY book_id DESC";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($books);
}

// Update Book
function updateBook($pdo)
{
    $book_id = isset($_GET['bookId']) ? $_GET['bookId'] : null;
    if (!$book_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Book ID is required']);
        exit;
    }

    // Get the current book status
    $sql = "SELECT * FROM tbl_book WHERE book_id = :book_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":book_id", $book_id);
    $stmt->execute();
    $result = $stmt->fetch();

    // Toggle the book status
    if ($result['book_status'] == "Available") {
        $new_status = "Borrowed";
    } else {
        $new_status = "Available";
    }


    // Determine the date field to update
    $dateField = ($new_status === 'Borrowed') ? "book_borrowed_date" : "book_returned_date";
    $dateValue = date('Y-m-d H:i:s'); // Current date and time

    // Update the book book_status and date in the database
    $sql = "UPDATE tbl_book SET book_status = :book_status, $dateField = :date WHERE book_id = :book_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":book_status", $new_status);
    $stmt->bindParam(":date", $dateValue);
    $stmt->bindParam(":book_id", $book_id);
    if($stmt->execute()) {
        // Retrieve the updated book data
        $sql = "SELECT * FROM tbl_book WHERE book_id = :book_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":book_id", $book_id);
        $stmt->execute();
        $updated_result = $stmt->fetch();

        echo json_encode([
            'success' => true,
            'message' => 'Book status updated successfully',
            'data' => $updated_result
        ]);
    } else {
        echo json_encode(['error' => 'Error updating book status']);
    }
} 

