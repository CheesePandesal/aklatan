<?php

// connection
require "../config/database.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        getBooks($pdo);
        break;
    case "POST":
        addBook($pdo);
        break;
    case "PUT":
        updateBook($pdo);
        break;
    case "DELETE":
        deleteBook($pdo);
        break;
    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid Request"]);
}

function deleteBook($pdo)
{
    try {
        $id = isset($_GET['bookId']) ? $_GET['bookId'] : null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Book ID is required']);
            die;
        }
        // Check if the book status is borrowed
    $sql = "SELECT * FROM tbl_book WHERE book_id = :book_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":book_id", $id);
    $stmt->execute();
    $result = $stmt->fetch();

    // Toggle the book status
    if ($result['book_status'] == "Borrowed") {
        http_response_code(500);
        echo json_encode(["error" => "Cannot delete a borrowed book"]);
        exit;
    } 
        $sql = "DELETE FROM tbl_book WHERE book_id = :b_id";
        //prepare
        $stmt = $pdo->prepare($sql);
        //bind
        $stmt->bindParam(":b_id", $id);
        //exec
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Book deleted successfully', 'success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Book not found']);
            }
        } else {
            echo json_encode(['error' => 'Error deleting book']);
        }
    } catch (PDOException $e) {
        //rollback
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
function getBooks($pdo)
{
    try {
        // get id and search query
        $id = isset($_GET['bookId']) ? $_GET['bookId'] : null;
        $searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : null;

        if ($id) {
            //sql
            $sql = "SELECT * FROM tbl_book WHERE book_id = :id";
            //prepare
            $stmt = $pdo->prepare($sql);
            //bind
            $stmt->bindParam(":id", $id);
            //execute
            $stmt->execute();
            //fetch
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($books);
            exit;
        } elseif ($searchQuery) {
            //sql
            $sql = "SELECT * FROM tbl_book WHERE book_title LIKE :searchQuery OR book_author LIKE :searchQuery OR book_publisher LIKE :searchQuery OR book_publication_year LIKE :searchQuery";
            //prepare
            $stmt = $pdo->prepare($sql);
            //bind
            $stmt->bindValue(":searchQuery", '%' . $searchQuery . '%');
            //execute
            $stmt->execute();
            //fetch
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($books);
            exit;
        } else {
            //sql
            $sql = "SELECT * FROM tbl_book";
            //prepare
            $stmt = $pdo->prepare($sql);
            //execute
            $stmt->execute();
            //fetch
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($books);
            exit;
        }
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

function addBook($pdo)
{
    try {

        // get the input
        $input = json_decode(file_get_contents("php://input"), true);

        $book_title = isset($input['book_title']) ? $input['book_title'] : null;
        $book_author = isset($input['book_author']) ? $input['book_author'] : null;
        $book_publisher = isset($input['book_publisher']) ? $input['book_publisher'] : null;
        $book_publication_year = isset($input['book_publication_year']) ? $input['book_publication_year'] : null;
        $book_status = isset($input['book_status']) ? $input['book_status'] : null;

        //validation
        if (!$book_title || !$book_author || !$book_publisher || !$book_publication_year || !$book_status) {
            echo json_encode([
                "error" => "All fields are required"
            ]);
            exit;
        }

        //prepare and execute the sql statemnt
        $sql = "INSERT INTO tbl_book (book_title, book_author, book_publisher, book_publication_year, book_status) 
            VALUES (:book_title, :book_author, :book_publisher, :book_publication_year, :book_status)";
        //prepare
        $stmt = $pdo->prepare($sql);
        //binding
        $stmt->bindParam(":book_title", $book_title);
        $stmt->bindParam(":book_author", $book_author);
        $stmt->bindParam(":book_publisher", $book_publisher);
        $stmt->bindParam(":book_publication_year", $book_publication_year);
        $stmt->bindParam(":book_status", $book_status);
        //execute
        if ($stmt->execute()) {

            $lastBookAdded = [
                'book_id' => $pdo->lastInsertId(),
                'book_title' => $book_title,
                'book_author' => $book_author,
                'book_publisher' => $book_publisher,
                'book_publication_year' => $book_publication_year,
                'book_status' => $book_status,
            ];
            echo json_encode([
                'success' => true,
                'message' => 'Book added successfully',
                'data' => $lastBookAdded
            ]);
        } else {
            echo json_encode(['error' => 'Error adding book']);
        }
    } catch (PDOException $e) {
        //rollback
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}


function updateBook($pdo)
{
    try {
        $book_id = isset($_GET['bookId']) ? $_GET['bookId'] : null;
        if (!$book_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Book ID is required']);
            exit;
        }
        // get the input
        $input = json_decode(file_get_contents("php://input"), true);

        $book_title = isset($input['book_title']) ? $input['book_title'] : null;
        $book_author = isset($input['book_author']) ? $input['book_author'] : null;
        $book_publisher = isset($input['book_publisher']) ? $input['book_publisher'] : null;
        $book_publication_year = isset($input['book_publication_year']) ? $input['book_publication_year'] : null;
        $book_status = isset($input['book_status']) ? $input['book_status'] : null;
        //validation
        if (!$book_title || !$book_author || !$book_publisher || !$book_publication_year || !$book_status) {
            echo json_encode([
                "error" => "All fields are required"
            ]);
            exit;
        }

        //prepare and execute the sql statemnt
        $sql = "UPDATE tbl_book SET book_title = :book_title, book_author = :book_author, book_publisher = :book_publisher, book_publication_year = :book_publication_year 
            WHERE book_id = :book_id";
        //prepare
        $stmt = $pdo->prepare($sql);
        //binding
        $stmt->bindParam(":book_title", $book_title);
        $stmt->bindParam(":book_author", $book_author);
        $stmt->bindParam(":book_publisher", $book_publisher);
        $stmt->bindParam(":book_publication_year", $book_publication_year);
        $stmt->bindParam(":book_id", $book_id);
        //execute
        if ($stmt->execute()) {

            $lastBookUpdated = [
                'book_id' => $book_id,
                'book_title' => $book_title,
                'book_author' => $book_author,
                'book_publisher' => $book_publisher,
                'book_publication_year' => $book_publication_year,
                'book_status' => $book_status,
            ];
            echo json_encode([
                'success' => true,
                'message' => 'Book upated successfully',
                'data' => $lastBookUpdated
            ]);
        } else {
            echo json_encode(['error' => 'Error updating book']);
        }
    } catch (PDOException $e) {
        //rollback
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
