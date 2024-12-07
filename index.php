<?php
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['user_role'] == 'Admin') {
        header('Location: views/admin/dashboard.php');
    } else if ($_SESSION['user_role'] == 'Librarian') {
        header('Location: views/librarian/borrow_return.php');
    }
}
?>;
