<?php

// connection
require "../config/database.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        getUsers($pdo);
        break;
    case "POST":
        addUser($pdo);
        break;
    case "PUT":
        updateUser($pdo);
        break;
    case "DELETE":
        deleteUser($pdo);
        break;
    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid Request"]);
}

function deleteUser($pdo)
{
    try {
        $id = isset($_GET['userId']) ? $_GET['userId'] : null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'User ID is required']);
            die;
        }
        $sql = "DELETE FROM tbl_user WHERE user_id = :u_id";
        //prepare
        $stmt = $pdo->prepare($sql);
        //bind
        $stmt->bindParam(":u_id", $id);
        //exec
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'User deleted successfully', 'success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'User not found']);
            }
        } else {
            echo json_encode(['error' => 'Error deleting user']);
        }
    } catch (PDOException $e) {
        //rollback
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
function getUsers($pdo)
{
    try {
        // get id and search query
        $id = isset($_GET['userId']) ? $_GET['userId'] : null;
        $searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : null;

        if ($id) {
            //sql
            $sql = "SELECT * FROM tbl_user WHERE user_id = :id";
            //prepare
            $stmt = $pdo->prepare($sql);
            //bind
            $stmt->bindParam(":id", $id);
            //execute
            $stmt->execute();
            //fetch
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
            exit;
        } elseif ($searchQuery) {
            //sql
            $sql = "SELECT * FROM tbl_user WHERE username LIKE :searchQuery OR user_email LIKE :searchQuery ORDER BY user_id DESC";
            //prepare
            $stmt = $pdo->prepare($sql);
            //bind
            $stmt->bindValue(":searchQuery", '%' . $searchQuery . '%');
            //execute
            $stmt->execute();
            //fetch
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
            exit;
        } else {
            //sql
            $sql = "SELECT * FROM tbl_user ORDER BY user_id DESC";
            //prepare
            $stmt = $pdo->prepare($sql);
            //execute
            $stmt->execute();
            //fetch
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
            exit;
        }
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

function addUser($pdo)
{
    try {

        // get the input
        $input = json_decode(file_get_contents("php://input"), true);

        $username = isset($input['username']) ? $input['username'] : null;
        $user_email = isset($input['user_email']) ? $input['user_email'] : null;
        $user_password = isset($input['user_password']) ? $input['user_password'] : null;
        $user_role = isset($input['user_role']) ? $input['user_role'] : null;

        //validation
        if (!$username || !$user_email || !$user_password || !$user_role) {
            echo json_encode([
                "error" => "All fields are required"
            ]);
            exit;
        }

        //prepare and execute the sql statemnt
        $sql = "INSERT INTO tbl_user (username, user_email, user_password, user_role) 
            VALUES (:username, :user_email, :user_password, :user_role)";
        //prepare
        $stmt = $pdo->prepare($sql);
        //binding
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":user_email", $user_email);
        $stmt->bindParam(":user_password", $user_password);
        $stmt->bindParam(":user_role", $user_role);
        //execute
        if ($stmt->execute()) {

            $lastUserAdded = [
                'user_id' => $pdo->lastInsertId(),
                'username' => $username,
                'user_email' => $user_email,
                'user_password' => $user_password,
                'user_role' => $user_role,
            ];
            echo json_encode([
                'success' => true,
                'message' => 'User added successfully',
                'data' => $lastUserAdded
            ]);
        } else {
            echo json_encode(['error' => 'Error adding user']);
        }
    } catch (PDOException $e) {
        //rollback
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}


function updateUser($pdo)
{
    try {
        $user_id = isset($_GET['userId']) ? $_GET['userId'] : null;
        if (!$user_id) {
            http_response_code(400);
            echo json_encode(['error' => 'User ID is required']);
            exit;
        }
        // get the input
        $input = json_decode(file_get_contents("php://input"), true);

        $username = isset($input['username']) ? $input['username'] : null;
        $user_email = isset($input['user_email']) ? $input['user_email'] : null;
        $user_password = isset($input['user_password']) ? $input['user_password'] : null;
        $user_role = isset($input['user_role']) ? $input['user_role'] : null;
        //validation
        if (!$username || !$user_email || !$user_password || !$user_role) {
            echo json_encode([
                "error" => "All fields are required"
            ]);
            exit;
        }

        //prepare and execute the sql statemnt
        $sql = "UPDATE tbl_user SET username = :username, user_email = :user_email, user_password = :user_password, user_role = :user_role 
            WHERE user_id = :user_id";
        //prepare
        $stmt = $pdo->prepare($sql);
        //binding
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":user_email", $user_email);
        $stmt->bindParam(":user_password", $user_password);
        $stmt->bindParam(":user_role", $user_role);
        $stmt->bindParam(":user_id", $user_id);
        //execute
        if ($stmt->execute()) {

            $lastUserUpdated = [
                'user_id' => $user_id,
                'username' => $username,
                'user_email' => $user_email,
                'user_password' => $user_password,
                'user_role' => $user_role,
            ];
            echo json_encode([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $lastUserUpdated
            ]);
        } else {
            echo json_encode(['error' => 'Error updating user']);
        }
    } catch (PDOException $e) {
        //rollback
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
