<?php

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_role'])) {
    header("Location: /library-system/views/login.php");
    exit();
    header("Location: /index.php");
    exit();
}
