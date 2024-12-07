<?php
include("../../config/database.php");
include("../../includes/auth.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script type="text/javascript" src="../../assets/js/main.js" defer></script>
    <script type="text/javascript" src="../../assets/js/book.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <li>
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
            <li class="active">
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
            <div>
                <h4><?php echo $_SESSION['username']; ?></h4>
                <strong>Admin</strong>
            </div>
        </header>
        <main>
            <div class="user-title">
                <h1>Manage <span class="user-name">Books</span></h1>
            </div>
            <!-- Modal container for Adding Books -->
            <div id="modal-container" style="display:none;">
                <!-- Modal content -->
                <div id="modal-content">
                    <span id="close-btn" class="close-btn">&times;</span>
                    <h2 id="modal-title">Add New Book</h2>
                    <form name="form" id="bookForm">
                        <!-- <div class="form_wrap">
                            <div class="form_item">
                                <label>Book ID</label>
                                <input type="text">

                            </div>
                        </div> -->
                        <div class="form_wrap">
                            <div class="form_item">
                                <label>Title</label>
                                <input type="text" id="titleInput">

                            </div>
                        </div>
                        <div class="form_wrap">
                            <div class="form_item">
                                <label>Author</label>
                                <input type="text" id="authorInput">

                            </div>
                        </div>
                        <div class="form_wrap form_grp">

                            <div class="form_item">
                                <label>Publisher</label>
                                <input type="text" id="publisherInput">

                            </div>
                            <div class="form_item">
                                <label>Publication Year</label>
                                <input type="text" id="publicationYearInput">

                            </div>
                        </div>

                        <div class="btn" id="btn">
                            <input type="submit" value="Add Book">
                        </div>
                    </form>
                </div>
            </div>

            <div class="book-container">

                <div class="container">
                    <div class="container-header" style="align-items: center;">

                        <h2>Books List</h2>
                        <!-- Button to trigger the modal -->
                        <div style="display: flex;">
                            <input type="text" id="search-input" placeholder="Search for a book..." style="background-color: #f9f9f9; border: none; width: 300px; margin: 0px; border-radius: 9px; padding: 13px 15px;">
                        </div>
                        <button id="add-btn">Add New Book</button>
                    </div>
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Book ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th>Publication Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="book-record">

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <div id="pagination">
                                        <!-- pagination links will be inserted here -->
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>


        </main>
    </div>
   
</body>

</html>