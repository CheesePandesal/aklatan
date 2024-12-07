<?php
include("../../config/database.php");
include("../../includes/auth.php");
date_default_timezone_set('Asia/Manila');
// echo "Username: " . $_SESSION['username'] . "\n";
// echo "User Role: " . $_SESSION['user_role'] . "\n";


// Query to get the total number of users from tbl_user
$query_users = "SELECT COUNT(*) as total_users FROM tbl_user";
$stmt_users = $pdo->prepare($query_users);
$stmt_users->execute();
$row_users = $stmt_users->fetch(PDO::FETCH_ASSOC);
$total_users = $row_users ? $row_users['total_users'] : 0; // Default to 0 if no result

// Query to get the total number of books from tbl_book
$query_books = "SELECT COUNT(*) as total_books FROM tbl_book";
$stmt_books = $pdo->prepare($query_books);
$stmt_books->execute();
$row_books = $stmt_books->fetch(PDO::FETCH_ASSOC);
$total_books = $row_books ? $row_books['total_books'] : 0;

// Query to get the list of books from tbl_book
$query_books = "SELECT book_id, book_title, book_author, book_publisher, book_status FROM tbl_book LIMIT 5";
$stmt_books = $pdo->prepare($query_books);
$stmt_books->execute();
$books = $stmt_books->fetchAll(PDO::FETCH_ASSOC); // Default to 0 if no result

// Query to get the list of users from tbl_user
$query_users = "SELECT user_id, username, user_email, user_role FROM tbl_user LIMIT 5";
$stmt_users = $pdo->prepare($query_users);
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC); // Default to 0 if no result

// Query to get the total number of borrowed books from tbl_book
$query_books = "SELECT COUNT(*) as total_books FROM tbl_book WHERE book_status = 'Borrowed'";
$stmt_books = $pdo->prepare($query_books);
$stmt_books->execute();
$row_books = $stmt_books->fetch(PDO::FETCH_ASSOC);
$total_borrowed_books = $row_books ? $row_books['total_books'] : 0;

// Query to get the total number of available books from tbl_book
$query_books = "SELECT COUNT(*) as total_books FROM tbl_book WHERE book_status = 'Available'";
$stmt_books = $pdo->prepare($query_books);
$stmt_books->execute();
$row_books = $stmt_books->fetch(PDO::FETCH_ASSOC);
$total_available_books = $row_books ? $row_books['total_books'] : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/confirmLogout.js"></script>
</head>

<body>

    <nav id="sidebar" style="display: flex; flex-direction: column; justify-content: space-between;">
        <ul>
            <li>
                <span class="logo">aklatan.</span>
                <button onclick=toggleSidebar() id="toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z" />
                    </svg>
                </button>
            </li>
            <li class="active">
                <a href="dashboard.php">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Zm80-360h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="manage_user.php">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Zm80 0h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z" />
                    </svg>
                    <span>Manage Users</span>
                </a>
            </li>
            <li>
                <a href="manage_books.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M6 22h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1H21V4c0-1.103-.897-2-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3zM5 8V5c0-.805.55-.988 1-1h13v12H5V8z" />
                        <path d="M8 6h9v2H8z" />
                    </svg>
                    <span>Manage Books</span>
                </a>
            </li>
            <li>
                <a href="reports.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M3 5v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2H5c-1.103 0-2 .897-2 2zm16.001 14H5V5h14l.001 14z"></path>
                        <path d="M11 7h2v10h-2zm4 3h2v7h-2zm-8 2h2v5H7z"></path>
                    </svg>
                    <span>Generate Report</span>
                </a>
            </li>



        </ul>
        <ul>
            <li>
                <a href=""></a>
            </li>
            <li>
                <a href="#" onclick="confirmLogout()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" transform: ;msFilter:;">
                        <path d="M16 13v-2H7V8l-5 4 5 4v-3z"></path>
                        <path d="M20 3h-9c-1.103 0-2 .897-2 2v4h2V5h9v14h-9v-4H9v4c0 1.103.897 2 2 2h9c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2z"></path>
                    </svg>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="main-container">
        <header>
            <h3></h3>
            <div class="flex-container" style="gap: 20px;">
                <div>

                    <h4><?php echo $_SESSION['username']; ?></h4>
                    <strong>Admin</strong>
                </div>
                <div>
                </div>
            </div>
        </header>
        <main>


            <div class="user-title">
                <h1>Hello, <span class="user-name"><?php echo $_SESSION['username']; ?>!</span></h1>
                <h5 class="date-and-time"><?php echo date("M j, Y | l, g:i A"); ?></h5>
            </div>
            <div class="flex-container" style="margin-top: 40px; gap: 20px;">
                <div class="container statistics">
                    <div class="flex-container" style="justify-content: space-between; align-items: center;">

                        <span class="number"><?php echo $total_users; ?></span>
                        <div class="circle"><span><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" style="fill: #fff; transform: ;msFilter:;">
                                    <path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path>
                                </svg>
                            </span></div>
                    </div>
                    <div style="margin-top: 20px; font-weight: bold;">
                        <span>Total Users</span>
                    </div>
                </div>
                <div class="container statistics">
                    <div class="flex-container" style="justify-content: space-between; align-items: center;">

                        <span class="number"><?php echo $total_books; ?></span>
                        <div class="circle"><span><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" style="fill: #fff;transform: ;msFilter:;">
                                    <path d="M6 22h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1H21V4c0-1.103-.897-2-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3zM5 8V5c0-.805.55-.988 1-1h13v12H5V8z"></path>
                                    <path d="M8 6h9v2H8z"></path>
                                </svg></span></div>
                    </div>
                    <div style="margin-top: 20px; font-weight: bold;">
                        <span>Total Books</span>
                    </div>
                </div>
                <div class="container statistics">
                    <div class="flex-container" style="justify-content: space-between; align-items: center;">

                        <span class="number"><?php echo $total_borrowed_books; ?></span>
                        <div class="circle"><span><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" style="fill: #fff;transform: ;msFilter:;">
                                    <path d="M21 8c-.202 0-4.85.029-9 2.008C7.85 8.029 3.202 8 3 8a1 1 0 0 0-1 1v9.883a1 1 0 0 0 .305.719c.195.188.48.305.729.28l.127-.001c.683 0 4.296.098 8.416 2.025.016.008.034.005.05.011.119.049.244.083.373.083s.254-.034.374-.083c.016-.006.034-.003.05-.011 4.12-1.928 7.733-2.025 8.416-2.025l.127.001c.238.025.533-.092.729-.28.194-.189.304-.449.304-.719V9a1 1 0 0 0-1-1zM4 10.049c1.485.111 4.381.48 7 1.692v7.742c-3-1.175-5.59-1.494-7-1.576v-7.858zm16 7.858c-1.41.082-4 .401-7 1.576v-7.742c2.619-1.212 5.515-1.581 7-1.692v7.858z"></path>
                                    <circle cx="12" cy="5" r="3"></circle>
                                </svg></span></div>
                    </div>
                    <div style="margin-top: 20px; font-weight: bold;">
                        <span>Borrowed Books</span>
                    </div>
                </div>
                <div class="container statistics">
                    <div class="flex-container" style="justify-content: space-between; align-items: center;">

                        <span class="number"><?php echo $total_available_books; ?></span>
                        <div class="circle"><span><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" style="fill: #fff;transform: ;msFilter:;">
                                    <path d="M19 2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.806 5 19s.55-.988 1.012-1H21V4c0-1.103-.897-2-2-2zm0 14H5V5c0-.806.55-.988 1-1h13v12z"></path>
                                </svg></span></div>
                    </div>
                    <div style="margin-top: 20px; font-weight: bold;">
                        <span>Available Books</span>
                    </div>
                </div>
            </div>
            <div class="book-and-user-container">


                <div class="container flex-item border-radius-large">
                    <div class="container-header">
                        <h2>Users List</h2>
                    </div>
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User Role</th>
                            </tr>
                        </thead>
                        <tbody id="user-record">
                            <?php foreach ($users as $user): ?>
                                <tr class="user-row">
                                    <td class="user-id"><?php echo htmlspecialchars($user['user_id']); ?></td>
                                    <td class="username"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="user-email"><?php echo htmlspecialchars($user['user_email']); ?></td>
                                    <td class="user-role" style="color: <?= ($user['user_role'] === 'Librarian') ? '#4aa86f' : '#f65867' ?>;"><?php echo htmlspecialchars($user['user_role']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="see-all-link">
                        <a href="./manage_user.php">See All</a>
                    </div>
                </div>
                <div class="container flex-item border-radius-large">
                    <div class="container-header">

                        <h2>Books List</h2>

                    </div>
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Book ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="book-record">
                            <?php foreach ($books as $book): ?>
                                <tr class="book-row">
                                    <td class="book-id"><?php echo htmlspecialchars($book['book_id']); ?></td>
                                    <td class="book-title"><?php echo htmlspecialchars($book['book_title']); ?></td>
                                    <td class="book-author"><?php echo htmlspecialchars($book['book_author']); ?></td>
                                    <td class="book-status" style="color: <?= ($book['book_status'] === 'Available') ? '#4aa86f' : '#f65867' ?>;"><?php echo htmlspecialchars($book['book_status']); ?></td>
                                </tr>
                            <?php endforeach; ?>


                        </tbody>
                    </table>
                    <div class="see-all-link">
                        <a href="./manage_books.php">See All</a>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>

</html>