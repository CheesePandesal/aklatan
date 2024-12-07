<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['user_role']);
session_destroy();
// Redirect the user to the login page
header("Location: ../views/login.php");
exit;
?>